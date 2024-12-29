<?php
if (!defined('_INCODE')) die('Access Deined...');

//Lấy dữ liệu cũ của nhóm người dùng
$body = getBody('get'); //Yêu cầu lấy phương thức get

if (!empty($body['id'])){
    $groupId = $body['id'];

    $groupDetail = firstRaw("SELECT * FROM `groups` WHERE id=$groupId");

    if (empty($groupDetail)){
        //Không Tồn tại
        redirect('admin?module=groups');
    }

}else{
    redirect('admin?module=groups');
}

$data = [
    'pageTitle' => 'Phân quyền: '.$groupDetail['name']
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);


//Xử lý cập nhật nhóm người dùng
if (isPost()){

    //Validate form
    $body = getBody(); //Lấy tất cả dữ liệu trong form

    $errors = []; //Mảng lưu trữ các lỗi


    if (empty($errors)){

        if (!empty($body['permissions'])){
            $permissionsJson = json_encode($body['permissions']);
        }else{
            $permissionsJson = '';
        }


        $dataUpdate = [
            'permission' => trim($permissionsJson),
            'update_at' => date('Y-m-d H:i:s')
        ];

        $condition = "id=$groupId";

        $updateStatus = update('groups', $dataUpdate, $condition);

        if ($updateStatus){
            setFlashData('msg', 'Phân quyền thành công');
            setFlashData('msg_type', 'success');

        }else{
            setFlashData('msg', 'Hệ thống đang gặp sự cố! Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');

        }

    }else{
        //Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
    }

    //Load lại trang sửa hiện tại
    redirect('admin?module=groups&action=permission&id='.$groupId);
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');


if (empty($old) && !empty($groupDetail)){
    $old = $groupDetail;
}

//Lấy danh sách Module
$moduleLists = getRaw("SELECT * FROM modules");

if (!empty($old['permission'])){
    $permissionsJson = $old['permission'];

    $permissionsArr = json_decode($permissionsJson, true);
}


?>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="" method="post">
                <?php
                getMsg($msg, $msgType);
                ?>

                <table class="table table-borderd permission-lists">
                    <thead>
                        <tr>
                            <th width="25%">Module</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (!empty($moduleLists)):
                                foreach ($moduleLists as $item):
                                    $actions = $item['actions'];
                                    $actionsArr = json_decode($actions, true);

                        ?>
                        <tr>
                            <td>
                                <strong><?php echo $item['title']; ?></strong>
                            </td>
                            <td>
                                <div class="row">
                                    <?php
                                    if (!empty($actionsArr)):
                                    foreach ($actionsArr as $roleKey => $roleTitle): ?>
                                    <div class="col-3">
                                        <input type="checkbox" name="<?php echo 'permissions['.$item['name'].'][]'; ?>" value="<?php echo $roleKey; ?>" id="<?php echo $item['name'].'_'.$roleKey; ?>" <?php echo (!empty($permissionsArr[$item['name']]) && in_array($roleKey, $permissionsArr[$item['name']]))?'checked':false; ?>/> <label for="<?php echo $item['name'].'_'.$roleKey; ?>"><?php echo $roleTitle; ?></label>
                                    </div>
                                    <?php endforeach; endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                                endforeach;
                           endif;
                        ?>
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Phân quyền</button>
                <a href="<?php echo getLinkAdmin('groups', 'lists'); ?>" class="btn btn-success">Quay lại</a>
            </form>
        </div>
    </section>

<?php
layout('footer', 'admin', $data);
