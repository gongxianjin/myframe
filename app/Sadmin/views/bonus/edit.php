<body style="overflow-x: hidden;background-color: #ededf0">
<section class="content">
    <div class="site">
        <img src="<?php echo __SADMIN_BASE__?>/images/site.png">
        <label><a href="<?php echo __SADMIN__?>/Bonus/index">充值奖品管理</a>&nbsp;>&nbsp;<a href="#">修改充值奖品</a></label>
    </div>
    <div class="addBox form_submit">
        <div class="inputBox">
            <p class="left">充值奖品名称：</p>
            <p class="right">
                <input type="text" name="type_name" placeholder="奖品名" value="<?php echo $lists['type_name']?>" />
            </p>
        </div>
        <div class="inputBox">
            <p class="left">充值数量：</p>
            <p class="right">
                <input type="text" name="chargenum" placeholder="充值数量" value="<?php echo $lists['chargenum']?>" />
            </p>
        </div>
        <div class="inputBox">
            <p class="left">赠送数量：</p>
            <p class="right">
                <input type="text" name="sendnum" placeholder="赠送数量" value="<?php echo $lists['sendnum']?>" />
            </p>
        </div>
        <div class="inputBox">
            <p class="left">最小使用数：</p>
            <p class="right">
                <input type="text" name="min_amount" placeholder="最小使用数" value="<?php echo $lists['min_amount']?>" />
            </p>
        </div>
        <div class="inputBox">
            <p class="left">最大使用数：</p>
            <p class="right">
                <input type="text" name="max_amount" placeholder="最大使用数" value="<?php echo $lists['max_amount']?>" />
            </p>
        </div>
        <div class="inputBox">
            <p class="left" style="line-height: 34px">开始时间：</p>
            <div class="rightDiv ">
                <form class="form-horizontal son" style="padding-top: 0;line-height: 34px">
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly  name="use_start_date" class="form-control birthday" value="<?php echo date('Y-m-d',($lists['use_start_date']/1000))?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="inputBox">
            <p class="left" style="line-height: 34px">结束时间：</p>
            <div class="rightDiv ">
                <form class="form-horizontal son" style="padding-top: 0;line-height: 34px">
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    </span>
                                    <input type="text" readonly  name="use_end_date" class="form-control birthday" value="<?php echo date('Y-m-d',($lists['use_end_date']/1000))?>" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <input type="hidden" name="ids"  value="<?php echo $lists['type_id'];?>" />
        <button class="btn btn-success sure ajax-post" type="button" pos="<?php echo __SADMIN__?>/Bonus/save_edit">确认修改</button>
    </div>
</section>

<script src="<?php echo __SADMIN_BASE__?>/javascript/lib/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo __SADMIN_BASE__?>/javascript/lib/moment.js"></script>
<script type="text/javascript" src="<?php echo __SADMIN_BASE__?>/javascript/lib/daterangepicker.js"></script>
<script>
    $(function(){

        $('#reservation').daterangepicker(null, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });

        /*日期选择插件*/
        $('.birthday').daterangepicker({
            singleDatePicker: true,format: 'YYYY-MM-DD'}, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });

        $('.ajax-post').click(function(){
            pos = $(this).attr('pos');
            data = $('.form_submit').find('input').serialize();
            $.ajax({
                type:'POST',
                url:pos,
                data:data,
                dataType:"json",
                success:function(data){
                    if(data.code == 200){
                        alert(data.msg);
                        window.location.href = data.forward;
                    }else{
                        alert(data.msg);
                    }
                }
            });
        });

    })
</script>
</body>
</html>