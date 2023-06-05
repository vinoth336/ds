<table class="display nowrap" id="example1" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th class="number"> {!! trans('core.abs_no') !!} </th>
			<th> <input type="checkbox" class="checkall" /></th>
			<th> {!! trans('core.abs_time') !!} </th>
			<th> {!! trans('core.abs_order_no') !!} </th>
			<th> {!! trans('core.abs_restaurant_name') !!} </th>
			<th> {!! trans('core.abs_order_Detail') !!} </th>
			<th width="70" >{!! Lang::get('core.btn_action') !!}</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($results as $key => $row)
			<tr>
				<td width="30"> <?php echo $key + 1;?> </td>
				<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" /></td>
				<td width="50">5374675346857</td>
				<td width="50">#{{$row->orderid}}</td>
				<?php $res_detail = $model->resname($row->orderid);?>
				<td width="50">{{$res_detail[0]->name}}</td>
				<td width="50">{{$row->order_details}}</td>									
			 <td>
				<!-- <i class="fa fa-volume-up"></i> -->
				<i class="icon-checkmark-circle2" aria-hidden="true"></i>
				<i class="icon-cancel-circle2" aria-hidden="true"></i>
			</td>
			</tr>
		@endforeach
	</tbody> 
</table>