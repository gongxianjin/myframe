<body class="SJTJ"  style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">数据统计</a>&nbsp;>&nbsp;<a href="#">参与数据统计</a></label>
    </div>
    <div class="navBox">
        <div class="navHead">
            <a href="<?php echo __SADMIN__?>/Datastatic/index">原始数据</a>
            <a href="<?php echo __SADMIN__?>/Userdatastatic/index">用户数据统计</a>
            <a href="<?php echo __SADMIN__?>/Joindatastatic/index">参与数据统计</a>
            <a class="selected" href="<?php echo __SADMIN__?>/Drawdatastatic/index">抽奖数据统计</a>
            <a href="<?php echo __SADMIN__?>/Zonedatastatic/index">区域数据统计</a>
        </div>
    </div>

    <div class="allData">
        <div class="numBox">
            <div class="num">
                <a href="<?php echo __SADMIN__?>/Drawdatastatic/index" class="int"><span>总数统计</span></a>
            </div>
            <div class="num">
                <a href="<?php echo __SADMIN__?>/Drawdatastatic/daystatic" class="int"><span>按日统计</span></a>
            </div>
            <div class="num">
                <a href="<?php echo __SADMIN__?>/Drawdatastatic/hourstatic" class="int selected"><span>按时统计</span></a>
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
                                    <input type="text" readonly  name="starttime" class="form-control birthday" value="<?php echo date('Y-m-d',time())?>" />
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
                                    <input type="text" readonly name="endtime" class="form-control birthday" value="<?php echo date('Y-m-d',time())?>" />
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
                            <option value="<?php echo  $item['id']?>" ><?php echo $item['materialsName']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="form-group son state">
                    <label>娇子币面额：</label>
                    <select class="form-control" name="td">
                        <option value="">全部</option>
                        <?php foreach($TdInfo as $item):?>
                            <option value="<?php echo  $item['price']?>" ><?php echo $item['price']?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="form-group son state">
                    <label>话费面额：</label>
                    <select class="form-control" name="md">
                        <option value="">全部</option>
                        <?php foreach($MdInfo as $item):?>
                            <option value="<?php echo  $item['price']?>" ><?php echo  $item['price']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <a class="btn btn-success"  href="<?php echo __SADMIN__?>/Drawdatastatic/draw_hourstatic_init">初始化</a>
                <div class="form-group son btnbox" style="float: right">
                    <button class="btn btn-success" type="button" id="search" pos="<?php echo __SADMIN__?>/Drawdatastatic/draw_hourstatic_init">初始搜索</button>
                    <button class="btn btn-info" type="button" id="export" pos="<?php echo __SADMIN__?>/Drawdatastatic/hourstatic_export">导出</button>
                </div>
            </div>
            <div class="tableBoxSon">
                <table class="table table-hover" style="width: 110%">
                    <thead>
                    <tr>
                        <th>日期</th>
                        <th>奖品发放份数</th>
                        <th>娇子币发放份数</th>
                        <th>娇子币发放个数</th>
                        <th>娇子币充值个数</th>
                        <th>话费发放份数</th>
                        <th>话费发放金额</th>
                        <th>话费充值金额</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lists as $item):?>
                        <tr>
                            <td><?php echo $item['dt1']?></td>
                            <td><?php echo $item['totalsends']?></td>
                            <td><?php echo $item['tdsends']?></td>
                            <td><?php echo $item['tdsendalls']?></td>
                            <td><?php echo $item['totaltdchargeCn']?></td>
                            <td><?php echo $item['mdsends']?></td>
                            <td><?php echo $item['mdsendalls']?></td>
                            <td><?php echo $item['totalmdchargeCn']?></td>
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
        window.location.href = url;
    });

</script>
