<!-- Sidebar -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo base_url(); ?>" style="font-family: cursive; font-style: italic; color: highlight;"><i class="fa fa-book"></i> Institute of Professional Learning</a>
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav side-nav">
			<li>
				<a href="<?php echo site_url('/issue_book')?>"><i class="fa fa-table"></i> Issued Books</a>
			</li>
			<li>
				<a href="<?php echo site_url('/student')?>"><i class="fa fa-edit"></i> Students <span class="label label-info" title="total no of student"><?php echo $this->framework->total_no_student();?></span></a>
			</li>
			<li>
				<a href="<?php echo site_url('/books')?>"><i class="fa fa-edit"></i> Books <span class="label label-info" title="total no of book"><?php echo $this->framework->total_no_book();?></span></a>
			</li>
			<li>
				<a href="<?php echo site_url('/rack')?>" title="Manage Rack" ><i class="fa fa-bars"></i> Rack </a>
			</li>
			<li>
				<a href="<?php echo site_url('/student/search')?>" title="Student Search" ><i class="fa fa-bars"></i> Student Search </a>
			</li>
			<li>
				<a href="<?php echo site_url('/books/search')?>" title="Book Search" ><i class="fa fa-bars"></i> Book Search </a>
			</li>
		</ul>

		<ul class="nav navbar-nav navbar-right navbar-user">
			<li class="dropdown messages-dropdown">
			<?php $_student_list = $this->framework->defaulter_student_list();?>
				<?php if($_student_list):?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Defaulters <span class="label label-danger"><?php echo count($_student_list)?></span> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="dropdown-header">
							<?php echo count($_student_list)?> Defaulters
						</li>
						<?php foreach ($_student_list as $student):?>
							<li class="message-preview">
								<a href="<?php echo site_url('/student/student_detail/'. $student->student_id)?>"> <span class="avatar"><img src="<?php echo Template::theme_url("img/defaulters.jpg")?>" style="width: 50px; height: 50px;"></span> <span class="name"><?php echo $student->name;?>:</span> <span class="message"> have to submit <?php echo $student->num_of_books;?> Books</span> <span class="time"><i class="fa fa-clock-o"></i> <?php echo date("d-m-Y", strtotime($student->return_date));?></span> </a>
							</li>
							<li class="divider"></li>
						<?php endforeach;?>
					</ul>
				<?php else :?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Defaulters <span class="label label-danger">0</span> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="message-preview">
							<a href="#"> <span class="avatar"><img src="http://placehold.it/50x50"></span> <span class="name">No Defaulter</span> <span class="message">Discipline is the bridge between goals and accomplishment.</span> <span class="time"><i class="fa fa-clock-o"></i></span> </a>
						</li>
					</ul>
				<?php endif;?>
			</li>
			<li class="dropdown user-dropdown">
				<a href="<?php echo site_url('logout'); ?>"><i class="fa fa-power-off"></i> <?php echo lang('bf_action_logout')?></a>
				<!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php //echo (isset($current_user->display_name) && !empty($current_user->display_name)) ? $current_user->display_name : ($this->settings_lib->item('auth.use_usernames') ? $current_user->username : $current_user->email); ?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php //echo site_url(SITE_AREA .'/settings/users/edit') ?>"><i class="fa fa-user"></i> Profile</a>
					</li>
					<li>
						<a href="#"><i class="fa fa-envelope"></i> Inbox <span class="badge">7</span></a>
					</li>
					<li>
						<a href="<?php //echo site_url(SITE_AREA .'/settings/users/edit') ?>"><i class="fa fa-gear"></i> Settings</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="<?php //echo site_url('logout'); ?>"><i class="fa fa-power-off"></i> <?php //echo lang('bf_action_logout')?></a>
					</li>
				</ul> -->
			</li>
		</ul>
	</div><!-- /.navbar-collapse -->
</nav>