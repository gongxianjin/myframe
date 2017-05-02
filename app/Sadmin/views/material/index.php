<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content QXGL">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">烟包规格管理</a></label>
    </div>
    <div class="navBox QXGLBox">
        <div class="tableBox">
            <div class="searchBox">
                <a href="<?php echo __SADMIN__?>/Material/add" class="downLoad Btn"><img src="<?php echo __SADMIN_BASE__?>/images/add.png">添加规格</a>
            </div>
        </div>
    </div>
    <div class="navBox GLYXX">
        <div class="tableBox">
            <div class="tableBoxSon">
                <table class="table table-hover" style="width: 100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>烟包规格名称</th>
                        <th>开始时间</th>
                        <th>结束时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lists as $item):?>
                        <tr>
                            <th><?php echo $item['id']?></th>
                            <th><?php echo $item['materialsName']?></th>
                            <th><?php echo date('Y-m-d',($item['starttime']/1000))?></th>
                            <th><?php echo date('Y-m-d',($item['endtime']/1000))?></th>
                            <th>
                                <a class="btn btn-warning" href="<?php echo __SADMIN__?>/Material/edit/ids/<?php echo $item['id']?>">修改</a>
                                <a class="btn btn-danger"  href="<?php echo __SADMIN__?>/Material/del/ids/<?php echo $item['id']?>">删除</a>
                            </th>
                        </tr>
                    <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>
