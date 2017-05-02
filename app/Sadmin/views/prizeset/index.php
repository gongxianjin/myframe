<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">奖品管理</a>&nbsp;>&nbsp;<a href="#">奖品设置</a></label>
    </div>
    <div class="navBox">
        <div class="navHead">
            <a class="selected" href="#">奖品设置</a>
<!--            <a href="">实物奖品管理</a>-->
            <a href="<?php echo __SADMIN__?>/Tobaccopoints/index">娇子币充值管理</a>
            <a href="<?php echo __SADMIN__?>/Mobilepoints/index">话费充值管理</a>
<!--            <a href="content_JPGL_HB.html">红包发放管理</a>-->
        </div>
    </div>
    <div class="allData">
        <div class="numBox">
            <div class="num">
                <p class="red int"><?php echo $Totalprizes;?></p>
                <p class="pName">奖品总数</p>
            </div>
            <div class="num">
                <p class="blue int"><?php echo $Totalsends;?></p>
                <p class="pName">已发放总数</p>
            </div>
            <div class="num">
                <p class="yellow int"><?php echo $userCn;?></p>
                <p class="pName">抽奖人数</p>
            </div>
            <div class="num">
                <p class="blue2 int"><?php echo $giftCn?></p>
                <p class="pName">中奖数</p>
            </div>
        </div>
    </div>
    <div class="navBox">
        <div class="tableBox">
            <div class="searchBox">
                <label class="HDName">四川中烟</label>
                <a href="<?php echo __SADMIN__?>/Prizeset/add" class="downLoad Btn"><img src="<?php echo __SADMIN_BASE__?>/images/add.png">添加奖品</a>
            </div>
            <div class="tableBoxSon">
                <table class="table table-hover" style="width: 130%">
                    <thead>
                    <tr>
                        <th>奖品规格</th>
<!--                        <th>奖品类型</th>-->
                        <th>奖品名称</th>
                        <th>总个数</th>
                        <th>已发放</th>
                        <th>每天发放上限</th>
                        <th>天已发放最大数</th>
                        <th>每小时发放上限</th>
                        <th>小时已发放最大数</th>
                        <th>中奖概率</th>
                        <th>openID总中奖数</th>
                        <th>openID每天中奖数</th>
                        <th>手机总中奖数</th>
                        <th>手机总已发放最大数</th>
                        <th>手机每天中奖数</th>
<!--                        <th>发放顺序</th>-->
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($prize as $key=>$item):?>
                    <tr>
                        <td><?php echo $item['gift_attr_name']?></td>
<!--                        <td>--><?php //echo $item['gift_type_name']?><!--</td>-->
                        <td><?php echo $item['giftname']?></td>
                        <td><?php echo $item['total']?></td>
                        <td style="color:red;"><?php echo $item['every_sends']?></td>
                        <td><?php echo $item['daymax']?></td>
                        <td style="color:red;"><?php echo $item['everyday_sends']?></td>
                        <td><?php echo $item['hourmax']?></td>
                        <td><?php echo $item['rate']?></td>
                        <td><?php echo $item['openid_totalmax']?></td>
                        <td><?php echo $item['openid_hourmax']?></td>
                        <td><?php echo $item['phone_totalmax']?></td>
                        <td style="color:red;"><?php echo $item['everymobile_sends']?></td>
                        <td><?php echo $item['phone_hourmax']?></td>
<!--                        <td>--><?php //echo $item['order']?><!--</td>-->
                        <td><div class="Btn red"><a href="<?php echo __SADMIN__?>/Prizeset/edit/ids/<?php echo $item['id']?>">修改</a></div></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>
<script src="<?php echo __SADMIN_BASE__?>/javascript/paging.js"></script>
<script>
    $(function(){
    })
</script>