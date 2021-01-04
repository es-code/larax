<?php


namespace Escode\Larax;


use Escode\Larax\App\Mail\laraxMail;
use Escode\Larax\App\Models\LaraxException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Larax
{
    private $exception;
    private $exception_data;
    private $enable_email;
    private $emails;
    private $ignore_headers;
    private $ignore_inputs;
    private $guards;
    private $detect_user;
    private $request;


    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;

        $this->exception_data=config('larax.exception_data');
        $this->enable_email=config('larax.enable_email');
        $this->emails=config('larax.emails');
        $this->ignore_headers=config('larax.ignore_headers');
        $this->ignore_inputs=config('larax.ignore_inputs');
        $this->guards=config('larax.guards');
        $this->detect_user=config('larax.detect_user');
        $this->request=request();


    }


    public static function exception(Throwable $exception) : bool {
        $larax = (new self($exception))->handler();
        return $larax;
    }

    private function handler() : bool {
       $save = $this->store_report();
       if ($save && $this->enable_email == true){
           $this->send_emails($save);
       }
        return (!$save)?false:true;
    }


    private function store_report() {
        return LaraxException::create($this->transform_data());
    }


    private function transform_data(){
        //fill our data based on config field
        $data=[];
        // check if detect user equal true
        if($this->detect_user == true)
            //push user key to exception data to detect it
            $this->exception_data = array_merge($this->exception_data,['user'=>true]);

        foreach ($this->exception_data as $field=>$value){
            if($value === true){
                // get value by key
                $k_value = $this->get_value_by_key($field);
                if($k_value)
                    //merge data with data transform array
                    $data=array_merge($data,$k_value);
            }
        }
        //add exception key to transform array
        return array_merge($data,[
            'exception'=>$this->exception,
        ]);
    }

    // get item value based on key
    private function get_value_by_key($key){
        switch ($key){
            case 'url':
                return ['url'=>$this->request->fullUrl()];
                break;
            case 'headers':
                return  ['headers'=>json_encode(array_diff_key($this->request->headers->all(),array_flip($this->ignore_headers)))];
                break;
            case 'body':
                return  ['body'=>json_encode(array_diff_key($this->request->all(),array_flip($this->ignore_inputs)))];
                break;
            case 'user':
                $user=$this->detect_user();
                return ['user_id'=>$user['id'],'guard'=>$user['guard']];
                break;
            case 'ip':
                return ['ip'=>$this->request->ip()];
            default:
                return null;
        }
    }


 //detect user based on config guards
    private function detect_user(){
        foreach ($this->guards as $guard){
            //check if user is authenticated with this guard
            if(auth($guard=='auth'?'':$guard)->check()){
                return ['id'=>auth($guard=='auth'?'':$guard)->id(),'guard'=>$guard];
            }
        }
        return ['id'=>0,'guard'=>null];
    }



    private function send_emails($exception){

        // use custom build html mail
        $mail = $this->buildMail($exception);

        foreach ($this->emails as $email) {

            $name = explode('@',env('MAIL_USERNAME'))[0];

            try {
                Mail::send(array(), array(), function ($message) use ($mail,$email,$name) {
                    $message->to($email)
    ->subject('Larax Exception')
    ->from(env('MAIL_USERNAME'),$name)
    ->setBody($mail, 'text/html');
});


            } catch (\Exception $e) {

              Log::critical("larax can't send exception mail\n".$e);
            }
        }
    }


    private function buildMail(LaraxException $exception){

        return str_ireplace(
            ["{{user_id}}", "{{guard}}", "{{ip}}","{{headers}}", "{{body}}", "{{exception}}","{{time}}"],
            [$exception->user_id,$exception->guard,$exception->ip,$exception->headers,$exception->body,$exception->exception,$exception->created_at]
            , file_get_contents(__DIR__.'/stubs/mail.stub')
        );
    }



}
