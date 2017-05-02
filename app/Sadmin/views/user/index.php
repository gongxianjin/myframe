<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content QXGL">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">用户管理</a></label>
    </div>
    <div class="navBox QXGLBox">
        <div class="tableBox">
            <div class="searchBox">
                <form class="search-form" action="<?php echo __SADMIN__?>/User/index">
                    <div class="form-group son searchMy">
                        <input type="text" class="form-control" value="<?php echo isset($sword)?$sword:'';?>" name="sword" placeholder="请输入手机号进行搜索">
                        <button class="btn btn-warning" type="button" id="search" pos="<?php echo __SADMIN__?>/User/index">搜索</button>
                    </div>
                </form>
                <a href="<?php echo __SADMIN__?>/User/export" class="btn btn-info">导出用户</a>
            </div>
        </div>
    </div>
    <div class="navBox GLYXX">
        <div class="tableBox">
            <div class="tableBoxSon">
                <table class="table table-hover" style="width: 100%">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>头像</th>
                        <th>微信ID</th>
                        <th>昵称</th>
                        <th>性别</th>
                        <th>手机号</th>
                        <th>参与时间</th>
                        <th>是否封号</th>
                        <th>封号原因</th>
                        <th>IP</th>
<!--                        <th>操作</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lists as $key=>$item):?>
                    <tr>
                        <th><?php echo $key+1?></th>
                        <th><img src='<?php echo $item['headimg'];?>' width="50" height="50"/> </th>
                        <th><?php echo $item['openid']?></th>
                        <th><?php echo $item['nickname']?></th>
                        <th><?php if($item['sex'] == 1){echo '男';}elseif($item['sex'] == 2){echo '女';}else{echo '其他';}?></th>
                        <th><?php echo $item['phone']?></th>
                        <th><?php echo date('Y-m-d H:i:s',$item['createtime'])?></th>
                        <th><input type="button" name="disabled" value="<?php if($item['disabled']){echo '是';}else{echo '否';}?>" <?php if($item['disabled']):?> onclick="unlock('<?php echo $item['id']?>');"<?php endif;?>></th>
                        <th><?php echo $item['disabledreason']?></th>
                        <th><?php echo $item['ip']?></th>
<!--                        <th>-->
<!--                            <a class="btn btn-warning" href="--><?php //echo __SADMIN__?><!--/User/edit/ids/--><?php //echo $item['id']?><!--">修改</a>-->
<!--                            <a class="btn btn-danger"  href="--><?php //echo __SADMIN__?><!--/User/del/ids/--><?php //echo $item['id']?><!--">删除</a>-->
<!--                        </th>-->
                    </tr>
                    <?php endforeach?>
                    </tbody>
                </table>
            </div>
            <!--分页器-->
            <?php echo $show?>
        </div>
    </div>
</section>

<script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>

<script>
    $('form').submit(function(){return false;});
    //搜索功能
    $("#search").click(function(){
        var url = $(this).attr('pos');
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        query = query.split("=");
        url += '/' + query[0]+'/'+query[1];
        if(url=='') return false;
        window.location.href = url;
    });

    function unlock(id){
        if(confirm('确认解锁')){
            var pos = '<?php echo __SADMIN__?>/User/unlock' ;
            $.ajax({
                type:'POST',
                url:pos,
                data:{'id':id},
                dataType:"json",
                success:function(data){
                    if(data.code == 200){
                        alert(data.msg);
                        window.location.reload();
                    }else{
                        alert(data.msg);
                    }
                }
            });
        }else{
            return false;
        }
    }

</script>