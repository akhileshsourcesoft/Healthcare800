<?php
use common\models\Category;
$categorylist = Category::find()->where(['parent_id'=>null])->andWhere(['status'=>'1'])->all();
if(count($categorylist)>0){ ?>
<div class="container">
  <div class="row">
    <div class="searchTabs">
      <ul id="searchTb" class="nav nav-tabs" role="tablist">
		<?php 
		$counter = 1;
		$categoryName = array();
		foreach($categorylist as $catValue){ 
		$categoryName[] = $catValue['category_name'];
		if($counter==1){ $active = 'class="active"';}else{ $active = '';}
		?>
        <li <?php echo $active;?>><a href="#tabs<?php echo $counter;?>" data-toggle="tab"><span><img src="<?php echo Yii::$app->getUrlManager()->createUrl(['uploads']).'/' . $catValue['image'];?>" width="28" height="28"><input type="hidden" name="categoryId" id="categoryId" value="<?php echo $catValue['category_id'];?>"></span><?php echo $catValue['category_name'];?></a></li>
        <?php $counter++; } ?>
      </ul>
      <?php
      $categoryNameArr = array_slice($categoryName,0,1);
      ?>
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tabs1">
            <!--<input name="searchtxt" id="searchtxt" type="text" hint="searches" placeholder="Search your <?php echo $categoryNameArr[0];?>, state, city, speciality, zipcode" onkeyup="autocomplet('<?php echo Yii::$app->getUrlManager()->createUrl('user/searchtext');?>')"><input name="searchHomebtn" id="searchHomebtn" type="button" onclick="searchHomepage();">-->

            <input type="hidden" id="hidval" name="searchtxt">
            <input name="searchhidden" id="searchtxt" type="text" hint="searches" placeholder="Search your <?php echo $categoryNameArr[0];?>, state, city, speciality, zipcode" onkeyup="autocomplet('<?php echo Yii::$app->getUrlManager()->createUrl('user/searchtext');?>')"><input name="searchHomebtn" id="searchHomebtn" type="button" onclick="searchHomepage();">
           
            <ul id="searchTextbox"></ul>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<script>
	var searchText = $("#searchtxt").val('');
	
	$(".searchTabs").on("click", 'li', function(){
	//	alert($(this).text());
	var on=$(this).attr('onclick');
	var searchText = $("#searchtxt").val();
	var searchCategory = $(this).text();
	if(searchText!=''){
	$("#searchtxt").val(searchCategory);
	}else{
	$("#searchtxt").attr("placeholder","Search your "+searchCategory+", state, city, speciality, zipcode");
	}
	});
	
	$(".searchTabs #tabs1").on("click", 'li', function(){
	 var newval=$(this).attr('onclick');
	 var newstr=newval.replace("set_item('","");
	 var filterdata=newstr.replace("')","");
	 $("#hidval").val(filterdata);
	
	 var searchText = $("#searchtxt").val();
		var searchCategory = $(this).text();
		
		if(searchText!=''){
			$("#searchtxt").val(searchCategory);
		}else{
			$("#searchtxt").attr("placeholder","Search your "+searchCategory+", state, city, speciality, zipcode");
		}
	
	});
	
</script>
<!--<script>
	var searchText = $("#searchtxt").val('');
	$(".searchTabs").on("click", 'li', function(){
		var searchText = $("#searchtxt").val();
		var searchCategory = $(this).text();
		if(searchText!=''){
			$("#searchtxt").val(searchText);
		}else{
			$("#searchtxt").attr("placeholder","Search your "+searchCategory+", state, city, speciality, zipcode");
		}
	});
</script>-->
