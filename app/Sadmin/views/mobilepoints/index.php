<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="#">奖品管理</a>&nbsp;>&nbsp;<a href="#">娇子币充值管理</a></label>
    </div>
    <div class="navBox">
        <div class="navHead">
            <a href="<?php echo __SADMIN__?>/Prizeset/index">奖品设置</a>
<!--            <a href="">实物奖品管理</a>-->
            <a href="<?php echo __SADMIN__?>/Tobaccopoints/index">娇子币充值管理</a>
            <a class="selected" href="#">话费充值管理</a>
<!--            <a href="content_JPGL_HB.html">红包发放管理</a>-->
        </div>
    </div>

    <div class="allData setting-form">
        <div class="numBox">
            <div class="num">
                <p class="red int"><input type="text" name="mobile_charge_max_limit" value="<?php echo $mobile_charge_max_limit;?>"></p>
                <p class="pName">总充值上限</p>
            </div>
            <div class="num">
                <p class="blue int"><?php echo $totalchargeCn;?></p>
                <p class="pName">已充值</p>
            </div>
            <div class="num">
                <p class="blue int"><?php echo $daytotalchargeCn;?></p>
                <p class="pName">今日已充值</p>
            </div>
            <div class="num">
                <p class="yellow int"><input type="text" name="mobile_charge_day_max_limit" value="<?php echo $mobile_charge_day_max_limit;?>"></p>
                <p class="pName">每日充值上限</p>
            </div>
            <div class="num">
                <p class="blue2 int"><input type="text" name="mobile_charge_hour_max_limit" value="<?php echo $mobile_charge_hour_max_limit;?>"></p>
                <p class="pName">每小时充值上限</p>
            </div>
            <div class="num">
                <p class="int sure" id="chargesetting" pos="<?php echo __SADMIN__?>/Mobilepoints/chargesetting"><img src="<?php echo __SADMIN_BASE__?>/images/sure.png"></p>
                <p class="pName">确认修改</p>
            </div>
        </div>
    </div>

    <div class="navBox">
        <div class="tableBox">
            <div class="searchBox search-form">
                <div class="form-group son state">
                    <label>充值状态：</label>
                    <select class="form-control" name="tpstate">
                        <option value="0" <?php if($tpstate == 0):?>selected<?php endif;?>>全部</option>
                        <option value="1" <?php if($tpstate == 1):?>selected<?php endif;?>>未充值</option>
                        <option value="2" <?php if($tpstate == 2):?>selected<?php endif;?>>充值成功</option>
                        <option value="3" <?php if($tpstate == 3):?>selected<?php endif;?>>充值失败</option>
                        <option value="4" <?php if($tpstate == 4):?>selected<?php endif;?>>非法充值</option>
                        <option value="5" <?php if($tpstate == 5):?>selected<?php endif;?>>重复充值</option>
                    </select>
                </div>
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
                <div class="form-group son searchMy">
                    <input type="text" class="form-control"  name="tel" placeholder="手机号" value="<?php echo $tel?>">
                </div>
                <div class="form-group son btnbox" style="float: right">
                    <button class="btn btn-success" type="button" id="search" pos="<?php echo __SADMIN__?>/Mobilepoints/index">搜索</button>
                    <button class="btn btn-info" type="button" id="export" pos="<?php echo __SADMIN__?>/Mobilepoints/export">导出</button>
                </div>
            </div>
            <div class="tableBoxSon">
                <table class="table table-hover" style="width: 100%">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>手机号码</th>
                        <th>昵称</th>
                        <th>userID</th>
                        <th>IP</th>
                        <th>中奖时间</th>
                        <th>话费面值</th>
                        <th>充值状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lists as $key=>$item):?>
                    <tr>
                        <th><?php echo $key+1;?></th>
                        <th><?php echo $item['phone'];?></th>
                        <th><?php echo $item['usernickname'];?></th>
                        <th><?php echo $item['userid']?></th>
                        <th><?php echo $item['ipaddr']?></th>
                        <th><?php echo date('Y-m-d H:i:s',$item['createtime']/1000)?></th>
                        <th><?php echo $item['point']?></th>
                        <th><?php echo $item['state']?></th>
                        <?php if($item['laststate'] != 0):?><th><a href="<?php echo __SADMIN__?>/MobileCharge/excuteHandCharge/chargeRecordId/<?php echo $item['id'];?>" style="color:red;">充值</a></th><?php endif;?>
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

    //充值设置功能
    $('#chargesetting').click(function(){
        pos = $(this).attr('pos');
        data = $('.setting-form').find('input').serialize();
        $.ajax({
            type:'POST',
            url:pos,
            data:data,
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
    });

</script>

