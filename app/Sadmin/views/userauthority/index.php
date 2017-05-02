<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content QXGL">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">权限管理</a></label>
    </div>
    <div class="navBox QXGLBox">
        <div class="tableBox">
            <div class="searchBox">
                <form class="search-form" action="<?php echo __SADMIN__?>/Userauthority/index">
                    <div class="form-group son searchMy">
                        <input type="text" class="form-control" value="<?php echo isset($sword)?$sword:'';?>" name="sword" placeholder="请输入手机号进行搜索">
                        <button class="btn btn-warning" type="button" id="search" pos="<?php echo __SADMIN__?>/Userauthority/index">搜索</button>
                    </div>
                </form>
                <a href="<?php echo __SADMIN__?>/Userauthority/add" class="downLoad Btn"><img src="<?php echo __SADMIN_BASE__?>/images/add.png">添加管理员</a>
            </div>
        </div>
    </div>
    <div class="navBox GLYXX">
        <div class="tableBox">
            <div class="tableBoxSon">
                <table class="table table-hover" style="width: 100%">
                    <thead>
                    <tr>
                        <th>员工姓名</th>
                        <th>绑定手机号</th>
                        <th>权限信息</th>
                        <th>权限时效</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lists as $item):?>
                    <?php if($item['id'] != 1):?>
                    <tr>
                        <th><?php echo $item['tname']?></th>
                        <th><?php echo $item['phone']?></th>
                        <th>
                            <span><?php echo $item['action_list']?></span>
                        </th>
                        <th><?php echo date('Y-m-d',strtotime($item['start_time']))?>-<?php echo date('Y-m-d',strtotime($item['end_time']))?></th>
                        <th>
                            <a class="btn btn-warning" href="<?php echo __SADMIN__?>/Userauthority/edit/ids/<?php echo $item['id']?>">修改</a>
                            <a class="btn btn-danger"  href="<?php echo __SADMIN__?>/Userauthority/del/ids/<?php echo $item['id']?>">删除</a>
                        </th>
                    </tr>
                    <?php endif;?>
                    <?php endforeach?>
                    </tbody>
                </table>
            </div>
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
</script>