<?php
if (!defined('_INCODE')) die('Access Deined...');

if (isPost()){
    $body = getBody();

    $errors = [];

    //Validate fullname
    if (empty(trim($body['fullname']))){
        $errors['fullname']['required'] = 'Tên không được để trống';
    }else{
        if (strlen(trim($body['fullname']))<5){
            $errors['fullname']['min'] = 'Tên phải >= 5 ký tự';
        }
    }

    //Validate email
    if (empty(trim($body['email']))){
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    }else{
        if (!isEmail(trim($body['email']))){
            $errors['email']['isEmail'] = 'Email không hợp lệ';
        }
    }

    if (empty($errors)){

        $dataInsert = [
            'fullname' => trim($body['fullname']),
            'email' => trim($body['email']),
            'status' => 0,
            'create_at' => date('Y-m-d H:i:s'),
        ];

        $insertStatus = insert('subscribe', $dataInsert);

        if ($insertStatus){

            setFlashData('msg', 'Đăng ký thành công');

            setFlashData('msg_type', 'success');

        }else{
            setFlashData('msg', 'Đăng ký không thành công! Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }

    }else{
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
    }

    $urlBack = $_SERVER['HTTP_REFERER'].'#newsletter';
    redirect($urlBack, true);
}