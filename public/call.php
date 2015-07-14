<!DOCTYPE html>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("button").click(function(){
 
   var person = '{id:"10", name:"Avishek123", "gender":"Male"}'; 

    $.post("http://huntinggame.localhost/admin/player/add",
    {
      data:person
    },
    function(data,status){
      alert("Data: " + data + "\nStatus: " + status);
    });
  });
});
</script>
</head>
<body>

<button>Send an HTTP POST request to a page and get the result back</button>




<?php 
/*$data1 = array('fname'=>"Avishe",'lname'=>"biswas");

  $data_string = json_encode($data1);

                $ch = curl_init("http://huntinggame.localhost/san-restful/client");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array("name"=>$data_string)));
                
                $response = curl_exec($ch);
                echo $response;
               // break;
*/


		   
?>				