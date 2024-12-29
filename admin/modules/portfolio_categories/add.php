<?php
//Lấy userId đăng nhập
$userId = isLogin()['user_id'];

if (isPost()){

    //Validate form
    $body = getBody(); //Lấy tất cả dữ liệu trong form

    $errors = []; //Mảng lưu trữ các lỗi

    //Validate tên danh mục: Bắt buộc nhập, >=4 ký tự
    if (empty(trim($body['name']))){
        $errors['name']['required'] = 'Tên danh mục bắt buộc phải nhập';
    }else{
        if (strlen(trim($body['name']))<4){
            $errors['name']['min'] = 'Họ tên phải >=4 ký tự';
        }
    }

    //Kiểm tra mảng $errors
    if (empty($errors)) {
        //Không có lỗi xảy ra

        $dataInsert = [
            'name' => trim($body['name']),
            'create_at' => date('Y-m-d H:i:s'),
            'user_id' => $userId
        ];

        $insertStatus = insert('portfolio_categories', $dataInsert);

        if ($insertStatus){
            setFlashData('msg', 'Thêm danh mục thành công');
            setFlashData('msg_type', 'success');

            redirect('admin?module=portfolio_categories'); //Chuyển hướng qua trang danh sách

        }else{
            setFlashData('msg', 'Hệ thống đang gặp sự cố! Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');

            redirect('admin?module=portfolio_categories'); //Load lại trang thêm danh mục người dùng
        }



    }else{

        //Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        redirect('admin?module=portfolio_categories'); //Load lại trang danh mục người dùng
    }
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

?>
<h4>Thêm danh mục</h4>
<form action="" method="post">
    <div class="form-group">
        <label for="">Tên danh mục</label>
        <input type="text" class="form-control" name="name" placeholder="Tên danh mục.." value="<?php echo old('name', $old); ?>"/>
        <?php echo form_error('name', $errors, '<span class="error">', '</span>'); ?>
    </div>
    <button type="submit" class="btn btn-primary">Thêm mới</button>
</form>