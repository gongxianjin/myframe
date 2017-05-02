<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="<?php echo __SADMIN__?>/User/index">权限管理</a>&nbsp;>&nbsp;<a href="#">修改管理员信息</a></label>
    </div>
    <div class="addBox form_submit">
        <div class="inputBox">
            <p class="left">员工姓名：</p>
            <p class="right">
                <input type="text" name="tname" placeholder="员工姓名" value="<?php echo $sysadmin['tname']?>" />
            </p>
        </div>
        <div class="inputBox">
            <p class="left">绑定手机号：</p>
            <p class="right">
                <input type="text" name="phone" placeholder="" value="<?php echo $sysadmin['phone']?>" />
            </p>
        </div>
        <div class="inputBox">
            <p class="left">旧密码：</p>
            <p class="right">
                <input type="text" name="oldpwd" placeholder="8-12位数字或英文字符" value="" />
            </p>
        </div>
        <div class="inputBox">
            <p class="left">新密码：</p>
            <p class="right">
                <input type="text" name="newpwd" placeholder="8-12位数字或英文字符" value="" />
            </p>
        </div>
        <div class="inputBox">
            <p class="left" style="line-height: 34px">权限时效：</p>
            <div class="rightDiv ">
                <form class="form-horizontal son" style="padding-top: 0;line-height: 34px">
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly  name="start_time" class="form-control birthday" value="<?php echo date('Y-m-d',strtotime($sysadmin['start_time']))?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <label style="width: 3%;display: block">-</label>
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly name="end_time" class="form-control birthday" value="<?php echo date('Y-m-d',strtotime($sysadmin['end_time']))?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="inputBox">
            <p class="left">权限分配：</p>
            <div class="rightDiv ">
                <div class="border">
                    <?php foreach($power as $key=>$val):?>
                    <div class="inputBox">
                        <p class="left"><?php echo $val['c_alias']?>：</p>
                        <p class="right">
                            <?php foreach ($val['item'] as $k=>$v):?>
                            <label class="checkbox">
                                <input type="checkbox" <?php if(isset($cname) && in_array($key.'-'.$k, $cname)):?>checked="checked"<?php endif;?> value="<?php echo $key?>-<?php echo $k?>" name="power_id[]"> <?php echo $v?>
                            </label>
                            <?php endforeach;?>
                        </p>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        <input type="hidden" value="<?php echo $sysadmin['id']?>" name="ids"/>
        <button class="btn btn-success sure ajax-post" type="button" pos="<?php echo __SADMIN__?>/User/save_edit">确认修改</button>
    </div>
</section>

<script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo __SADMIN_BASE__?>/javascript/lib/moment.js"></script>
<script type="text/javascript" src="<?php echo __SADMIN_BASE__?>/javascript/lib/daterangepicker.js"></script>
<script>
    $(function(){

        $('#reservation').daterangepicker(null, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });

        /*日期选择插件*/
        $('.birthday').daterangepicker({
            singleDatePicker: true,format: 'YYYY-MM-DD'}, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });

        $('.ajax-post').click(function(){
            pos = $(this).attr('pos');
            data = $('.form_submit').find('input').serialize();
            $.ajax({
                type:'POST',
                url:pos,
                data:data,
                dataType:"json",
                success:function(data){
                    if(data.code == 200){
                        alert(data.msg);
                        window.location.href = data.forward;
                    }else{
                        alert(data.msg);
                    }
                }
            });
        });

    })
</script>
</body>
</html>