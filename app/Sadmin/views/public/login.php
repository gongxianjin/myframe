<article style="width: 800px">
    <form action="<?php echo __SADMIN__?>/Public/CheckLogin" method="post"  name="form1" id="form1">
        <div>
            <p>请输入手机号：</p>
            <input type="text" name="phone" id="txtPhone"  placeholder="手机号">
            <p>请输入验证码：</p>
            <p class="YAN">
                <input type="code" name="code" id="txtCode" placeholder="验证码">
                <label>获取验证码</label>
            </p>
            <p>请输入密码：</p>
            <input type="password" name="password" id="txtPwd" placeholder="密码">
            <span><input type="submit" value="登录" name="submit" onclick="return clientClick();"></span>
        </div>
    </form>
</article>

<script type="text/javascript">
    function clientClick() {
        var txtPhone = document.getElementById("txtPhone").value;
        var txtCode = document.getElementById("txtCode").value;
        var userPwd = document.getElementById("txtPwd").value;
        if(txtPhone==""){
            alert("手机号不能为空");
            return false;
        }
        if(txtCode==""){
            alert("验证码不能为空");
            return false;
        }
        if (userPwd=="") {
            alert("密码不能为空");
            return false;
        }
        return true;
    }
</script>