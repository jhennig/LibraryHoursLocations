<div class="hour_grouping index twelve columns nest">
    <div class="first six columns"><h2><?php __('Dashboard');?></h2></div>
    <div class="last six columns text-right">
        <?php
			// don't show add button for branch users
            if($_SERVER['REMOTE_USER'] == 'hours'  || $_SERVER['REMOTE_USER'] == 'hours_admin') { 
            	echo $this->Html->link(__('Edit Locations',true),array('controller'=>'hour_locations','action'=>'index'),array('class'=>'medium button'));
            	echo '<span style="width:10px;">&nbsp;</span>';
            	echo $this->Html->link(__('Edit Date Ranges', true), array('controller'=>'hour_date_ranges','action' => 'index'),array('class'=>'medium button'));
            	echo '<span style="width:10px;">&nbsp;</span>';            	
            	echo $this->Html->link(__('Add Hours', true), array('action' => 'add'),array('class'=>'medium button'));	            
			}	
		?>
	</div>
	<div>	
        <?php echo $form->create('HourGrouping',array('action'=>'filter'));?>
		<div class="searchform">		
		<?php
			// restrict location and type to admin users; branches can only edit their location's reference hours
			if($_SERVER['REMOTE_USER'] == 'hours' || $_SERVER['REMOTE_USER'] == 'hours_admin') { 
				//options for location select field
	            $loptions=array();
	            $loptions[] = array('any'=>'- any location -');
	            foreach($locations as $id=>$name) {
	                $loptions[$id]=$name;
	            }
	            $lselected = '';
	            // set selected value based on session variable
	            if(!empty($session_location)) {
	            	$lselected = $this->data['Filter']['hour_location_id'] = $session_location;
	            }
	            // form data takes precedence over session data
	            if(isset($this->passedArgs['Filter.hour_location_id'])) {
	                $lselected = $this->data['Filter']['hour_location_id'] = $this->passedArgs['Filter.hour_location_id'];
	            }
	            //options for type select field
	            $toptions=array();
	            $toptions[] = array('any'=>'- any type -');
	            foreach($types as $id=>$name) {
	                $toptions[$id]=$name;
	            }
	            $tselected = '2';
	            if(!empty($session_type)) {
	            	$tselected = $this->data['Filter']['hour_type_id'] = $session_type;
	            } 
	            if(isset($this->passedArgs['Filter.hour_type_id'])) {
	                $tselected = $this->data['Filter']['hour_type_id'] = $this->passedArgs['Filter.hour_type_id'];
	            }          
	            //options for category select field
	            $coptions=array();
	            $coptions[] = array('any'=>'- any category -');
	            foreach($categories as $id=>$name) {
	                $coptions[$id]=$name;
	            }
	            $cselected = '';
	            if(!empty($session_category)) {
	            	$cselected = $this->data['Filter']['hour_category_id'] = $session_category;
	            }
	            if(isset($this->passedArgs['Filter.hour_category_id'])) {
	                $cselected = $this->data['Filter']['hour_category_id'] = $this->passedArgs['Filter.hour_category_id'];
	            }
	            //options for date range select field
	            $doptions=array();
	            $doptions[] = array('any'=>'- any date range -');
	            foreach($dates as $key=>$value) {
	                $doptions[$key]=$value;
	            }
	            $dselected = '';
				if(!empty($session_date_range)) {
	            	$dselected = $this->data['Filter']['hour_date_range_id'] = $session_date_range;
	            }
	            if(isset($this->passedArgs['Filter.hour_date_range_id'])) {
	                $dselected = $this->data['Filter']['hour_date_range_id'] = $this->passedArgs['Filter.hour_date_range_id'];
	            }            
	            
	            echo '<div class="first twelve columns">';
				echo '<label for="FilterHourLocationId">Filter by any combination of date range, category, location, or library/reference hours type</label>';
				echo '</div>';            
	            echo '<div class="first three columns">';				
				echo $form->input('Filter.hour_date_range_id',array('label'=>false,'options'=>$doptions,'selected'=>$dselected,'div'=>false));
				echo '</div>';
	            echo '<div class="two columns">';				
				echo $form->input('Filter.hour_category_id',array('label'=>false,'options'=>$coptions,'selected'=>$cselected,'div'=>false));
				echo '</div>';
	            echo '<div class="three columns">';
	            echo $form->input('Filter.hour_location_id',array('label'=>false,'options'=>$loptions,'selected'=>$lselected,'div'=>false));
				echo '</div>';
				echo '<div class="two columns">';	
				echo $form->input('Filter.hour_type_id',array('label'=>false,'options'=>$toptions,'selected'=>$tselected,'div'=>false));
				echo '</div>';
				echo '<div class="one column">';
				echo $form->submit('Filter',array('label'=>false,'div'=>false, 'class'=>'medium button'));
				echo '</div>';
				echo '<div class="last one column">';
				echo $form->submit('Clear',array('label'=>false,'div'=>false,'name'=>'ClearFilter','class'=>'medium button'));
				echo '</div>';
				echo '<div class="clear"></div>';
			}
		?>
		</div>
        <?php 
        	echo $form->end();
        ?>
   </div>

    <table cellpadding="0" cellspacing="0">
	<tr>
			<?php if($_SERVER['REMOTE_USER'] == 'hours' || $_SERVER['REMOTE_USER'] == 'hours_admin') { // these fields not needed for branches ?>
			<th><?php echo $this->Paginator->sort('Begin Date', 'HourDateRange.begin_date');?></th>
			<th><?php echo $this->Paginator->sort('End Date','HourDateRange.end_date');?></th>
			<?php } // end if admin login ?>
			<th><?php echo $this->Paginator->sort('Category','HourCategory.category');?></th>
			<th><?php echo $this->Paginator->sort('Location','HourLocation.name');?></th>
			<th><?php echo $this->Paginator->sort('Type','HourType.type');?></th>


	</tr>
	<?php
	$i = 0;
	foreach ($hour_groupings as $range):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<?php if($_SERVER['REMOTE_USER'] == 'hours' || $_SERVER['REMOTE_USER'] == 'hours_admin') { // these fields not needed for branches ?>
		<td nowrap="nowrap">
			<?php 
				if($range['HourType']['type'] != 'Reference') {
					echo $this->Html->link(date("F d, Y",strtotime($range['HourDateRange']['begin_date'])), array('action' => 'edit', $range['HourGrouping']['id']), array('title'=>$range['HourDateRange']['description'])); 
				} else { 
					echo $this->Html->link('N/A', array('action' => 'edit', $range['HourGrouping']['id'])); 
				} 
			?>
		</td>
		<td nowrap="nowrap">
			<?php 
				if($range['HourType']['type'] != 'Reference') { 
					echo $this->Html->link(date("F d, Y",strtotime($range['HourDateRange']['end_date'])), array('action' => 'edit', $range['HourGrouping']['id']), array('title'=>$range['HourDateRange']['description'])); 
				} else { 
					echo $this->Html->link('N/A', array('action' => 'edit', $range['HourGrouping']['id'])); 
				} 
			?>
		</td>
		<?php } // end if admin login ?>
		<td nowrap="nowrap">
			<span class="hours-category <?php if ($range['HourCategory']['category'] == 'Summer Alternate') { echo 'summer-alternate'; } else { echo strtolower($range['HourCategory']['category']); } ?>"></span>
			<?php echo $this->Html->link($range['HourCategory']['category'], array('action' => 'edit', $range['HourGrouping']['id']), array('title'=>$range['HourDateRange']['description'])); ?>
		</td>
        <td><?php echo $this->Html->link($range['HourLocation']['name'], array('action' => 'edit', $range['HourGrouping']['id']), array('title'=>$range['HourDateRange']['description'])); ?></td> 
		<td><?php echo $this->Html->link($range['HourType']['type'], array('action' => 'edit', $range['HourGrouping']['id']), array('title'=>$range['HourDateRange']['description'])); ?></td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
                <?php
                    $count = $this->params['paging'][$paginator->defaultModel()]['count']; //total count of records
                    $limit = $this->params['paging'][$paginator->defaultModel()]['options']['limit'];//records to be shown on a page
                    if($limit < $count) {
                        echo $this->Html->link(__('show all', true), array('action' => 'index', 'show:all'));
                    }
                ?>
	</div>
</div>

<script>
	$('td').mouseover(function() {
		$(this).css("background-color","#F3F3F3");
		$(this).siblings().css("background-color","#F3F3F3");	
	});
	$('td').mouseleave(function() {
		$(this).css("background-color","#fff");
		$(this).siblings().css("background-color","#fff");	
	});
	
</script>