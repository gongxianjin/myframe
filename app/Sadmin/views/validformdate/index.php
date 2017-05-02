<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content QXGL">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">配置有效时间管理</a></label>
    </div>
    <div class="navBox QXGLBox">
        <div class="tableBox">
            <div class="searchBox">
                <a href="<?php echo __SADMIN__?>/Validformdate/add" class="downLoad Btn"><img src="<?php echo __SADMIN_BASE__?>/images/add.png">添加有效时间</a>
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
                        <th>开始时间</th>
                        <th>结束时间</th>
                        <th>创建时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lists as $item):?>
                        <tr>
                            <th><?php echo $item['id']?></th>
                            <th><?php echo $item['starttime']?></th>
                            <th><?php echo $item['endtime']?></th>
                            <th><?php echo date('Y-m-d h:i:s',($item['createtime']))?></th>
                        </tr>
                    <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>
