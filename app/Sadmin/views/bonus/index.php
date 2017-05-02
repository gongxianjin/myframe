<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content QXGL">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">充值奖品管理</a></label>
    </div>
    <div class="navBox QXGLBox">
        <div class="tableBox">
            <div class="searchBox">
                <a href="<?php echo __SADMIN__?>/Bonus/add" class="downLoad Btn"><img src="<?php echo __SADMIN_BASE__?>/images/add.png">添加充值奖品</a>
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
                        <th>充值奖品名称</th>
                        <th>充值数量</th>
                        <th>赠送数量</th>
                        <th>最大使用数</th>
                        <th>最小使用数</th>
                        <th>开始时间</th>
                        <th>结束时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lists as $item):?>
                        <tr>
                            <th><?php echo $item['type_id']?></th>
                            <th><?php echo $item['type_name']?></th>
                            <th><?php echo $item['chargenum']?></th>
                            <th><?php echo $item['sendnum']?></th>
                            <th><?php echo $item['min_amount']?></th>
                            <th><?php echo $item['max_amount']?></th>
                            <th><?php echo date('Y-m-d',($item['use_start_date']/1000))?></th>
                            <th><?php echo date('Y-m-d',($item['use_end_date']/1000))?></th>
                            <th>
                                <a class="btn btn-warning" href="<?php echo __SADMIN__?>/Bonus/edit/ids/<?php echo $item['type_id']?>">修改</a>
                                <a class="btn btn-danger"  href="<?php echo __SADMIN__?>/Bonus/del/ids/<?php echo $item['type_id']?>">删除</a>
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
