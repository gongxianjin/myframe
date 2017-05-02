<body class="SJTJ"  style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">数据统计</a>&nbsp;>&nbsp;<a href="#">原始数据</a></label>
    </div>
    <div class="navBox">
        <div class="navHead">
            <a class="selected" href="<?php echo __SADMIN__?>/Datastatic/index">原始数据</a>
            <a href="<?php echo __SADMIN__?>/Userdatastatic/index">用户数据统计</a>
            <a href="<?php echo __SADMIN__?>/Joindatastatic/index">参与数据统计</a>
            <a href="<?php echo __SADMIN__?>/Drawdatastatic/index">抽奖数据统计</a>
            <a href="<?php echo __SADMIN__?>/Zonedatastatic/index">区域数据统计</a>
        </div>
    </div>

    <div class="allData">
        <div class="numBox">
            <div class="num">
                <a href="<?php echo __SADMIN__?>/Datastatic/index" class="int"><span>抽奖数据</span></a>
            </div>
            <div class="num">
                <a href="<?php echo __SADMIN__?>/Datastatic/tobaccocharge" class="int selected"><span>娇子币充值数据</span></a>
            </div>
            <div class="num">
                <a href="<?php echo __SADMIN__?>/Datastatic/mobilecharge" class="int"><span>话费充值数据</span></a>
            </div>
        </div>
    </div>

    <div class="navBox">
        <div class="tableBox">
            <div class="searchBox search-form">
                <form class="form-horizontal son">
                    <label style="width: 13%">时间段：</label>
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly  name="starttime" class="form-control birthday" value="<?php if($starttime):?><?php echo $starttime?><?php else:?><?php echo date('Y-m-d',time())?><?php endif;?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <label style="width: 3%">-</label>
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly name="endtime" class="form-control birthday" value="<?php if($endtime):?><?php echo $endtime?><?php else:?><?php echo date('Y-m-d',time())?><?php endif;?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <div class="form-group son state">
                    <label>烟包规格：</label>
                    <select class="form-control" name="mid">
                        <option value="">全部</option>
                        <?php foreach($materialsnames as $item):?>
                        <option value="<?php echo  $item['id']?>" <?php if($item['id'] == $mid):?>selected<?php endif;?> ><?php echo  $item['materialsName']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
		<a class="btn btn-success"  href="<?php echo __SADMIN__?>/Datastatic/tobaccocharge_init">初始化</a>
                <div class="form-group son btnbox" style="float: right">
                    <button class="btn btn-success" type="button" id="search" pos="<?php echo __SADMIN__?>/Datastatic/tobaccocharge">搜索</button>
                    <button class="btn btn-info" type="button" id="export" pos="<?php echo __SADMIN__?>/Datastatic/tobaccocharge_export">导出</button>
                </div>
            </div>
            <div class="tableBoxSon">
                <table class="table table-hover" style="width: 110%">
                    <thead>
                    <tr>
                        <th>二维码ID</th>
                        <th>烟包规格</th>
                        <th>用户手机号</th>
                        <th>省份</th>
                        <th>城市</th>
                        <th>运营商</th>
                        <th>昵称</th>
                        <th>openID</th>
                        <th>IP</th>
                        <th>奖品名称</th>
                        <th>充值面额</th>
                        <th>充值时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lists as $item):?>
                    <tr>
                        <td><?php echo $item['barcode'];?></td>
                        <td><?php echo $item['materialsName'];?></td>
                        <td><?php echo $item['phone'];?></td>
                        <td><?php echo $item['provance'];?></td>
                        <td><?php echo $item['city'];?></td>
                        <td><?php echo $item['mobile_type']?></td>
                        <td><?php echo $item['nickname'];?></td>
                        <td><?php echo $item['openid']?></td>
                        <td><?php echo $item['ip']?></td>
                        <td><?php echo $item['giftname']?></td>
                        <td><?php echo $item['point']?></td>
                        <td><?php echo date('Y-m-d H:i:s',$item['createtime']/1000);?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            <!--分页器-->
            <?php echo $show?>
        </div>
    </div>

</section>


<script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>
<script src="<?php echo __SADMIN_BASE__?>/javascript/paging.js"></script>
<script type="text/javascript" src="<?php echo __SADMIN_BASE__?>/javascript/lib/moment.js"></script>
<script type="text/javascript" src="<?php echo __SADMIN_BASE__?>/javascript/lib/daterangepicker.js"></script>
<script>
    $(function(){
        /*日期选择插件*/
        $('.birthday').daterangepicker({
            singleDatePicker: true ,
            timePicker: false,
            timePickerIncrement: 30,
            format: 'YYYY-MM-DD'}, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    })

    $('form').submit(function(){return false;});
    //搜索功能
    $("#search").click(function(){
        var url = $(this).attr('pos');
        var query  = $('.search-form').find('select,input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        query = query.replace(/[\\&=]/g,"/");
        url = url+'/'+query;
        if(url=='') return false;
        window.location.href = url;
    });

    //导出功能
    $("#export").click(function(){
        var url = $(this).attr('pos');
        var query  = $('.search-form').find('select,input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        query = query.replace(/[\\&=]/g,"/");
        url = url+'/'+query;
        if(url=='') return false;
        window.location.href = url;
    });

</script>
