<?php
function gen_rand_char(){
    // $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    // // Output: 54esmdr0qf
    // echo substr(str_shuffle($permitted_chars), 0, 10);
    
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    // Output: video-g6swmAP8X5VG4jCi.mp4
    return substr(str_shuffle($permitted_chars), 0, 10);
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['pay'])) {
    try{
        
        if(isset($_POST['email']) && isset($_POST['fullname']) && isset($_POST['price']) && isset($_POST['funding'])){

            $payment = ['user'=>$_SESSION['id'], 'price'=>$_POST['price'], 
            'units'=>$_POST['units'], 'funding'=>$_POST['funding'], 'roi'=>$_POST['roi'],
            'username'=>$_SESSION['username'], 'email'=>$_SESSION['email'],
            'status'=>0];
        
            $_SESSION["payment"] = $payment;
            
            
        
            // print_r($_SESSION["payment"]);
            // print_r($_POST);
        
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $pricing = $_POST['price'];
            
            $curl = curl_init();

            // $email = "your@email.com";
            // $amount = 30000;  //the amount in kobo. This value is actually NGN 300
            
            // url to go to after payment
            $callback_url = 'https://farmapropos.com/payment-verification';  
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => json_encode([
                'amount'=>$pricing*100,
                'email'=>$email,
                'callback_url' => $callback_url,
                'metadata' => $payment,
              ]),
              CURLOPT_HTTPHEADER => [
                "authorization: Bearer sk_test_2191c1d89190d49843f52326c4b4957e3d78c1b0", //replace this with your own test key
                "content-type: application/json",
                "cache-control: no-cache"
              ],
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            if($err){
              // there was an error contacting the Paystack API
              die('Curl returned error: ' . $err);
            }
            
            $tranx = json_decode($response, true);
            
            if(!$tranx['status']){
              // there was an error from the API
              print_r('API returned error: ' . $tranx['message']);
            }
            
            // comment out this line if you want to redirect the user to the payment page
            // print_r($tranx);
            // redirect to page so User can pay
            // uncomment this line to allow the user redirect to the payment page
            header('Location: ' . $tranx['data']['authorization_url']);
        } else {
            throw new Exception("please fill in the payment details reqiured.");
        }
        
    } catch (Exception $e) {
        $error = $e->getMessage();
        // echo $error;
        // header("Location: checkout?error=".$error."&funding=".$_SESSION['funding']."&units=".$_SESSION['units']);
    }
}

// FLUTTER WAVE PAYMENT GATEWAY API END POINT BELOW HERE
// echo "rave-29933838-pay-".gen_rand_char();
        
            // payment processing code from below here
        
            // $curl = curl_init();
        
            // $customer_email = $email;
            // $amount = $pricing;  
            // $currency = "NGN";
            // $txref = "rave-29933838-pay-".gen_rand_char(); // ensure you generate unique references per transaction.
            // // $PBFPubKey = "FLWPUBK_TEST-18669b721551bff222a2d53c2faea414-X"; // get your public key from the dashboard.
            // $PBFPubKey = "FLWPUBK_TEST-88f449ec9380499a52fd3380792931d2-X"; // this is the olanrewaju account public key here
            // $redirect_url = "https://farmapropos.com/payment-process";
        
            // curl_setopt_array($curl, array(
            // CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
            // CURLOPT_RETURNTRANSFER => true,
            // CURLOPT_CUSTOMREQUEST => "POST",
            // CURLOPT_POSTFIELDS => json_encode([
            //     'amount'=>$amount,
            //     'customer_email'=>$customer_email,
            //     'currency'=>$currency,
            //     'txref'=>$txref,
            //     'PBFPubKey'=>$PBFPubKey,
            //     'redirect_url'=>$redirect_url
            // ]),
            // CURLOPT_HTTPHEADER => [
            //     "content-type: application/json",
            //     "cache-control: no-cache"
            // ],
            // ));
        
            // $response = curl_exec($curl);
            // $err = curl_error($curl);
        
            // if($err){
            // // there was an error contacting the rave API
            // die('Curl returned error: ' . $err);
            // }
        
            // $transaction = json_decode($response);
            
            // // print_r($transaction);
        
            // if(!$transaction->data && !$transaction->data->link){
            // // there was an error from the API
            // print_r('API returned error: ' . $transaction->message);
            // }
        
            // // uncomment out this line if you want to redirect the user to the payment page
            // //print_r($transaction->data->message);
        
        
            // // redirect to page so User can pay
            // // uncomment this line to allow the user redirect to the payment page
            // header('Location: ' . $transaction->data->link);