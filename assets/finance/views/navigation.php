
<div class="sidebar-menu">
	<div class="sidebar-menu-inner">  
    
    <header class="logo-env">

				<!-- logo -->
				<div class="logo">
					<a href="index.html">
						        <img src="<?php echo base_url();?>assets/finance/images/logo.png"  class="" width="200"/>
					</a>
				</div>

				<!-- logo collapse icon -->
				<div class="sidebar-collapse">
					<a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
						<i class="entypo-menu"></i>
					</a>
				</div>

								
				<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
				<div class="sidebar-mobile-menu visible-xs">
					<a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
						<i class="entypo-menu"></i>
					</a>
				</div>

			</header>
	
    <ul id="main-menu" class="">
    
    <!-- Cash Journal -->
    	
    <li class="<?php
        if ($this->get_view() == 'show_journal' ||
                $this->get_view() == 'create_voucher' ||
                $this->get_view() == 'show_voucher' ||
                    $this->get_view() == 'cheque_book')
                        echo 'opened active';
        ?> ">
            <a href="#">
                <i class="fa fa-folder"></i>
                <span><?php echo $this->l('journal'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($this->get_view() == 'show_journal') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_journal","lib"=>"journal"));?>">
                        <span><i class="fa fa-folder-open"></i> <?php echo $this->l('cash_journal'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'create_voucher') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"create_voucher","lib"=>"journal"));?>">
                        <span><i class="fa fa-plus-square"></i> <?php echo $this->l('create_voucher'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'cheque_book') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"cheque_book","lib"=>"journal"));?>">
                        <span><i class="fa fa-check-square-o"></i> <?php echo $this->l('add_cheque_book'); ?></span>
                    </a>
                </li>
				               
            </ul>
        </li>
		
	<!--Budget-->	

    <li class="<?php
        if ($this->get_view() == 'show_budget' ||
                $this->get_view() == 'show_budget_summary' ||
                    $this->get_view() == 'show_budget_schedules' ||
                    $this->get_view() == 'edit_budget_item' ||
						$this->get_view() == 'create_budget_item')
                        echo 'opened active';
        ?> ">
            <a href="#">
                <i class="fa fa-book"></i>
                <span><?php echo $this->l('budget'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($this->get_view() == 'show_budget') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_budget","lib"=>"budget"));?>">
                        <span><i class="fa fa-briefcase"></i> <?php echo $this->l('full_budget'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_budget_summary') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_budget_summary","lib"=>"budget"));?>">
                        <span><i class="fa fa-map-signs"></i> <?php echo $this->l('budget_summary'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_budget_schedules') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_budget_schedules","lib"=>"budget"));?>">
                        <span><i class="fa fa-newspaper-o"></i> <?php echo $this->l('budget_schedules'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'create_budget_item') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"create_budget_item","lib"=>"budget"));?>">
                        <span><i class="fa fa-cart-plus"></i> <?php echo $this->l('create_budget_item'); ?></span>
                    </a>
                </li>
				               
            </ul>
        </li>
        
        <!--MFR-->
        
        <li class="<?php
        if ($this->get_view() == 'show_report' ||
                $this->get_view() == 'show_fundbalance' ||
                    $this->get_view() == 'show_proofcash' ||
						$this->get_view() == 'show_bankreconcile' ||
							$this->get_view() == 'show_outstandingcheques' || 
								$this->get_view() == 'show_transitdeposit' ||
									$this->get_view() == 'show_clearedcheques' ||
										$this->get_view() == 'show_cleareddeposits' ||
											$this->get_view() == 'show_budgetvariance' ||
												$this->get_view() == 'show_expensebreakdown' ||
												$this->get_view() == 'show_ratios' ||
													$this->get_view() == 'validate_report')
                        echo 'opened active';
        ?> ">
            <a href="#">
                <i class="fa fa-bar-chart"></i>
                <span><?php echo $this->l('report'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($this->get_view() == 'show_report') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_report","lib"=>"report"));?>">
                        <span><i class="fa fa-money"></i> <?php echo $this->l('financial_report'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_fundbalance') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_fundbalance","lib"=>"report"));?>">
                        <span><i class="fa fa-credit-card"></i> <?php echo $this->l('fund_balance'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_proofcash') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_proofcash","lib"=>"report"));?>">
                        <span><i class="fa fa-calculator"></i> <?php echo $this->l('proof_of_cash'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_bankreconcile') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_bankreconcile","lib"=>"report"));?>">
                        <span><i class="fa fa-bank"></i> <?php echo $this->l('bank_reconciliation'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_outstandingcheques') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_outstandingcheques","lib"=>"report"));?>">
                        <span><i class="fa fa-leaf"></i> <?php echo $this->l('oustanding_cheques'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_transitdeposit') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_transitdeposit","lib"=>"report"));?>">
                        <span><i class="fa fa-tags"></i> <?php echo $this->l('in_transit_deposit'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_clearedcheques') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_clearedcheques","lib"=>"report"));?>">
                        <span><i class="fa fa-bell-slash-o"></i> <?php echo $this->l('cleared_cheques'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_cleareddeposits') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_cleareddeposits","lib"=>"report"));?>">
                        <span><i class="fa fa-bell-slash"></i> <?php echo $this->l('cleared_deposits'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_budgetvariance') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_budgetvariance","lib"=>"report"));?>">
                        <span><i class="fa fa-hourglass-half"></i> <?php echo $this->l('budget_variance'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_expensebreakdown') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_expensebreakdown","lib"=>"report"));?>">
                        <span><i class="fa fa-list"></i> <?php echo $this->l('expense_breakdown'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($this->get_view() == 'show_ratios') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"show_ratios","lib"=>"report"));?>">
                        <span><i class="fa fa-magic"></i> <?php echo $this->l('financial_ratios'); ?></span>
                    </a>
                </li>
                
                <li class="<?php if ($this->get_view() == 'validate_report') echo 'active'; ?> ">
                    <a href="<?=$this->get_url(array("assetview"=>"validate_report","lib"=>"report"));?>">
                        <span><i class="fa fa-thumbs-o-up"></i> <?php echo $this->l('validate_report'); ?></span>
                    </a>
                </li>
				               
            </ul>
        </li>

               

    </ul>

</div>

</div>
