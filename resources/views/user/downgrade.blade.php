@extends('layouts.app')


@section('content')
	
	<div class="row">
		
		<div class="col-md-8 col-md-offset-2"> 
			
			<div class="panel panel-default">
				
				<div class="panel-heading">Downgrade</div>

				<div class="panel-body">
					
					<ul class="list-group">
						
						@if( $users != NULL )

							@foreach($users as $user)

								<li class="list-group-item">
									
									{{ $user->name }} : {{ $user->roles()->first()->name }} <br>
									
									Real Permission:

									<ul class="list-group">

										@foreach( json_decode( $user->roles()->first()->permissions , true ) as $permission => $value )							
											@if($value)

												<li class="list-group-item">{{ $permission . '=>' . 'true' }}</li>
											@else

												<li class="list-group-item">{{ $permission . '=>' . 'false' }}</li>

											@endif

										@endforeach

										@if($user->permissions != NULL)
											Updated Permissions : <br>


											@foreach( json_decode( $user->permissions , true ) as $permission => $value)

												@if($value)

													<li class="list-group-item">{{ $permission . '=>' . 'true' }}</li>
												@else

													<li class="list-group-item">{{ $permission . '=>' . 'false' }}</li>

												@endif	

											@endforeach

										@endif

									</ul>

									<div class="pull-right">
										
										<form action="downgrade/{{ $user->name }}" method="POST">
											
											{{ csrf_field() }}

											show :<input type="checkbox" name="list[]" value="show">
											
											Create:<input type="checkbox" name="list[]" value="create">

											Edit:<input type="checkbox" name="list[]" value="edit">

											Delete:<input type="checkbox" name="list[]" value="delete">

											Approve:<input type="checkbox" name="list[]" value="approve">

											Permission Level 

											<select name="permission_level" class="form-control">

												<option value="admin">Administrator</option>
												<option value="moderator">Moderator</option>
												<option value="user">Normal User</option>
												
											</select>

											<input type="submit" value="Upgrade User">

										</form>

									</div>

								</li>

							@endforeach

						@else

							<p class="text-center">No User To Upgrade</p>

						@endif

					</ul>

				</div>


			</div>

		</div>

	</div>

@endsection

