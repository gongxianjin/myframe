<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">修改密码</a></label>
    </div>
    <div class="addBox form_submit">
        <div class="inputBox">
            <p class="left">员工姓名：</p>
            <p class="right">
                <input type="text" name="tname" placeholder="员工姓名" value="<?php echo $adminuser['tname']?>" readonly/>
            </p>
        </div>
        <div class="inputBox">
            <p class="left">旧密码：</p>
            <p class="right">
                <input type="password" name="oldpwd"/>
            </p>
        </div>
        <div class="inputBox">
            <p class="left">新密码：</p>
            <p class="right">
                <input type="password" name="newpwd" placeholder="8-12位数字或英文字符"/>
            </p>
        </div>
        <div class="inputBox">
            <p class="left">再次输入新密码：</p>
            <p class="right">
                <input type="password" name="password_confirm" placeholder="8-12位数字或英文字符"/>
            </p>
        </div>
        <input type="hidden" value="<?php echo $adminuser['id'];?>" name="id"/>
        <button class="btn btn-success sure ajax-post" type="button" pos="<?php echo __SADMIN__?>/Sysuser/save_selfuser">确认修改</button>
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