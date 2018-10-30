<?php
	echo $this->Form->create();

	echo $this->Form->input('ShippingAddress.first_name');
	echo $this->Form->input('ShippingAddress.last_name');
	echo $this->Form->input('ShippingAddress.address');
	echo $this->Form->input('ShippingAddress.address2');
	echo $this->Form->input('ShippingAddress.city');
	echo $this->Form->input('ShippingAddress.zip');
	echo $this->Form->input('ShippingAddress.country');

	echo $this->Form->input('BillingAddress.same_as_shipping', array(
		'type' => 'checkbox'));

	echo $this->Form->input('BillingAddress.first_name');
	echo $this->Form->input('BillingAddress.last_name');
	echo $this->Form->input('BillingAddress.address');
	echo $this->Form->input('BillingAddress.address2');
	echo $this->Form->input('BillingAddress.city');
	echo $this->Form->input('BillingAddress.zip');
	echo $this->Form->input('BillingAddress.country');

	echo $this->Form->end();
?>