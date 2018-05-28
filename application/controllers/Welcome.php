<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends MY_Controller {

var $apiKey;
var $listId;
var $dataCenter;

    public function __construct() {
        parent::__construct();
        $this->load->model('global_model'); 

        $this->apiKey = 'a6d89dfa40f65bd0a1ca41673cfe6a2e-us11';    
        $this->listId = '76c07cc268';    
        $this->dataCenter =  substr($this->apiKey,strpos($this->apiKey,'-')+1);   
    }



    public function index()
    {
        $this->load->view('add_subscriber');
    }

    public function subscriber_post()
    {
        $firstname = $this->input->post('firstname');
        $lastname = $this->input->post('lastname');
        $email = $this->input->post('email');
        
        //API Details
        $memberId = md5($email);
        // $memberId = md5(strtolower($email));
        
        $url = 'https://' . $this->dataCenter . '.api.mailchimp.com/3.0/lists/' 
        . $this->listId . '/members/' . $memberId;

        //Member info
        $data = array(
            'email_address'=>$email,
            'status' => 'subscribed',
            'merge_fields'  => [
                'FNAME'     => $firstname,
                'LNAME'     => $lastname
            ]
            );
        $jsonString = json_encode($data);
        
        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        echo "Subscriber added";
        echo "<br/>";
        echo $response;
        echo "<br/>";
        echo "<a href='". base_url('welcome')."'>Go back</a>" ;   

    }

    public function view_all_lists()
    {
        $url = 'https://' . $this->dataCenter . '.api.mailchimp.com/3.0/lists/';        

        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // print_r($httpCode);
        echo "<pre>";
        
        $response = json_decode($response);

        echo "<a href='".base_url('welcome')."'>Go Home</a>";
        echo "<br/>";

        echo "Total Lists on your mailchimp account: ". $response->total_items;
        echo "<br/>";
        echo "+++++++++++++++++++++++++++++++++++"; 
        echo "<br/>";
        // print_r($response->lists);
        
            foreach($response->lists as $list)
            {
                echo "List Name: ".$list->name;
                echo "<br/>";
                print_r($list->stats);
                echo "<br/>";     
                echo "<a href='".base_url('welcome/view_list?id=').$list->id."'>Get List Members</a><br/>";           
                echo "+++++++++++++++++++++++++++++++++++";  
                echo "<br/>";                
            } 
    }

    public function view_list()
    {
        $listId = $this->input->get('id');

        $url = 'https://' . $this->dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members';        

        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // print_r($httpCode);
        echo "<pre>";
        
        $response = json_decode($response);
        // print_r($response->members);
        
            foreach($response->members as $member)
            {
                print_r($member->merge_fields);
                echo "<br/>";
                print_r($member->email_address);
                echo "<br/>";     
                echo "<a href='".base_url('welcome/member_get?id=').$member->id."'>Get Member Details</a><br/>";           
                echo "+++++++++++++++++++++++++++++++++++";  
                echo "<br/>";                
            } 
    }

    public function member_get()
    {
        $member_id = $this->input->get('id');
        $url = 'https://' . $this->dataCenter . '.api.mailchimp.com/3.0/lists/' . $this->listId . '/members/'.$member_id;        

        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "HTTP Responce Code: ".$httpCode;
        echo "<pre>"; 
        $response = json_decode($response);  
        
        echo "<br/>";
        echo "<a href='".base_url('welcome/view_all_lists')."'>Go To Lists</a>";
        echo "<br/>";
        echo "<br/>";
        print_r($response);
    }


    

}
