<article>
    <section id="left">
        <div class="navbar">
            <ul class="nav">
                <li class="dropdown">
                    <ul class="dropdown-menu" style="display: block">
                        <?php if($POWER_Userauthority):?>
                        <li><a class="selected" id="<?php echo __SADMIN__?>/Userauthority/index">权限管理</a></li>
                        <?php endif;?>
                        <?php if($POWER_Prizeset):?>
                            <li><a id="<?php echo __SADMIN__?>/Prizeset/index">奖品管理</a></li>
                        <?php endif;?>
                        <?php if($POWER_Material):?>
                            <li><a id="<?php echo __SADMIN__?>/Material/index">烟包规格管理</a></li>
                        <?php endif;?>
                        <?php if($POWER_Bonus):?>
                            <li><a id="<?php echo __SADMIN__?>/Bonus/index">充值奖品管理</a></li>
                        <?php endif;?>
                        <?php if($POWER_Validformdate):?>
                            <li><a id="<?php echo __SADMIN__?>/Validformdate/index">有效时间管理</a></li>
                        <?php endif;?>
                        <?php if($POWER_User):?>
                            <li><a id="<?php echo __SADMIN__?>/User/index">用户管理</a></li>
                        <?php endif;?>
                        <?php if($POWER_Export):?>
                            <li><a id="<?php echo __SADMIN__?>/Export/index">报表管理</a></li>
                        <?php endif;?>
                        <?php if($POWER_Datastatic):?>
                            <li><a id="<?php echo __SADMIN__?>/Datastatic/index">数据统计</a></li>
                        <?php endif;?>
                    </ul>
                </li>
            </ul>
        </div>
    </section>
    <section id="right">
        <iframe src="<?php echo __SADMIN__?>/Userauthority/index" width="100%" frameborder="0" height="100%"></iframe>
    </section>
    <script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>
    <script>
        $(function(){
            var articleHeight = $(window).height()-60;
            $("article").css("height",articleHeight);

            //点击具体活动名称调用函数
            $(".dropdown-menu").find("a").click(function(){
                if($(this).attr("class")!="selected"){
                    $(".dropdown-menu").find("a").attr("class","");
                    $(this).attr("class","selected");
                    var srcV = $(this).attr("id");
                    $("iframe").eq(0).attr("src",srcV);
                }
            });
            $("#XGMM").click(function(){
                $("iframe").eq(0).attr("src","<?php echo __SADMIN__?>/Sysuser/selfuser");
            })
        })
    </script>
</article>
