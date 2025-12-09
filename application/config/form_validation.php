<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(	

                 'user_confirm' => array(
                                    array(
                                            'field' => 'name',
                                            'label' => 'Name',
                                            'rules' => 'trim|required|min_length[3]|max_length[150]|xss_clean'
                                         ),
								    array(
                                            'field' => 'company_name',
                                            'label' => 'Compzny Name',
                                            'rules' => 'trim|required|min_length[3]|max_length[150]|xss_clean'
                                         ),
									array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email'
                                         ),
                                    array(
                                            'field' => 'mobile',
                                            'label' => 'Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),	


                                  ),
								  
                 'record' => array(
                                    array(
                                            'field' => 'material',
                                            'label' => 'Material',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'name',
                                            'label' => 'Name',
                                            'rules' => 'trim|required|min_length[3]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'mobile',
                                            'label' => 'Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),
                                   
								   array(
                                            'field' => 'company',
                                            'label' => 'Company',
                                            'rules' => 'trim|required|max_length[250]|xss_clean'
                                         ),
                                     array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email'
                                         ),
										 

                                  ),	

					'careers' => array(
											array(
											'field' =>'job_title',
											'label' => 'job_title',
											'rules' => 'trim|required|xss_clean'
											),
										
											array(
											'field' =>'name',
											'label' => 'name',
											'rules' => 'trim|required|xss_clean'
											),
										
											array(
												 'field' => 'email',
												 'label' => 'email',
												 'rules' => 'trim|required|valid_email|xss_clean'
												 ),
											
											array(
												 'field' => 'mobile',
												 'label' => 'mobile',
												 'rules' =>'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
												 ),
											
											array(
												 'field' => 'experience',
												 'label' => 'experience',
												 'rules' => 'trim|required|xss_clean'
												 ),
											
											array(
												 'field' => 'education',
												 'label' => 'education',
												 'rules' => 'trim|required|xss_clean'
												 ),
						
		                                ), 	

                 'property_form' => array(
                                    array(
                                            'field' => 'name',
                                            'label' => 'Name',
                                            'rules' => 'trim|required|min_length[3]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'mobile',
                                            'label' => 'Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),
                                   
								   array(
                                            'field' => 'company_name',
                                            'label' => 'Company Name',
                                            'rules' => 'trim|required|max_length[250]|xss_clean'
                                         ),
										 
								   array(
                                            'field' => 'area_req',
                                            'label' => 'Area Name',
                                            'rules' => 'trim|required|max_length[250]|xss_clean'
                                         ),	
										 
                                     array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email'
                                         ),
										 
                                     array(
                                            'field' => 'purpose',
                                            'label' => 'Purpose',
                                            'rules' => 'trim|required'
                                         ),										 
										

                                     array(
                                            'field' => 'additional_inf',
                                            'label' => 'Additional Information',
                                            'rules' => 'trim|required|min_length[3]|max_length[250]|xss_clean'
                                         ),

                                  ),										


                 'order_confirm' => array(
                                    array(
                                            'field' => 'name',
                                            'label' => 'Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'mobile',
                                            'label' => 'Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),
                                   /*
								   array(
                                            'field' => 'alt_mobile',
                                            'label' => 'Alt Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),
                                     array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email|xss_clean'
                                         ),
										 */
                                     array(
                                            'field' => 'vehicle',
                                            'label' => 'Vehicle',
                                            'rules' => 'trim|required|min_length[1]|max_length[20]|xss_clean'
                                         ),
                                     array(
                                            'field' => 'vehicle_no',
                                            'label' => 'Vehicle No',
                                            'rules' => 'trim|required|min_length[1]|max_length[50]|xss_clean'
                                         ),
										 /*
                                     array(
                                            'field' => 'vehicle_brand',
                                            'label' => 'Vehicle Brand',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                     array(
                                            'field' => 'vehicle_color',
                                            'label' => 'Vehicle Color',
                                            'rules' => 'trim|required|min_length[1]|max_length[50]|xss_clean'
                                         ),
                                     array(
                                            'field' => 'identification_details',
                                            'label' => 'Identification Details',
                                            'rules' => 'trim|required|min_length[1]|max_length[250]|xss_clean'
                                         ),
										 */

                                    /* array(
                                            'field' => 'bhours',
                                            'label' => 'Break Fast Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                     array(
                                            'field' => 'bminutes',
                                            'label' => 'Break Fast Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                     array(
                                            'field' => 'bmeridium',
                                            'label' => 'Break Fast Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),

                                     array(
                                            'field' => 'lhours',
                                            'label' => 'Lunch Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                     array(
                                            'field' => 'lminutes',
                                            'label' => 'Lunch Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                     array(
                                            'field' => 'lmeridium',
                                            'label' => 'Snacks Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),

                                     array(
                                            'field' => 'shours',
                                            'label' => 'Snacks Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                     array(
                                            'field' => 'sminutes',
                                            'label' => 'Snacks Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                     array(
                                            'field' => 'smeridium',
                                            'label' => 'Snacks Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),

                                     array(
                                            'field' => 'dhours',
                                            'label' => 'Dinner Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                     array(
                                            'field' => 'dminutes',
                                            'label' => 'Dinner Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                     array(
                                            'field' => 'dmeridium',
                                            'label' => 'Dinner Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),*/


                                  ),

				'extracharges' => array(
						array(
								'field' => 'service_tax',
								'label' => 'Service Tax',
								'rules' => 'trim|required|min_length[1]|max_length[11]|xss_clean'
							 ),
						array(
								'field' => 'vat_tax',
								'label' => 'Vat Tax',
								'rules' => 'trim|required|min_length[1]|max_length[11]|xss_clean'
							 ),
						array(
								'field' => 'from_price',
								'label' => 'From Price',
								'rules' => 'trim|required|min_length[1]|max_length[11]|xss_clean'
							 ),
						array(
								'field' => 'to_price',
								'label' => 'To Price',
								'rules' => 'trim|required|min_length[1]|max_length[11]|xss_clean'
							 ),
						array(
								'field' => 'delivery_charges',
								'label' => 'Delivery Charges',
								'rules' => 'trim|required|min_length[1]|max_length[11]|xss_clean'
							 ),
						), 

                 'menu' => array(
                                    array(
                                            'field' => 'city',
                                            'label' => 'City Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'restaurants',
                                            'label' => 'Restaurant Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'item_type',
                                            'label' => 'Item Type',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'category',
                                            'label' => 'Category Name',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'item_name',
                                            'label' => 'Item Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'item_price',
                                            'label' => 'Price',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'item_description',
                                            'label' => 'Description',
                                            'rules' => 'trim|required|min_length[1]|max_length[250]|xss_clean'
                                         ),
                                    ),


				'category' => array(
						array(
								'field' => 'name',
								'label' => 'Category Name',
								'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
							 )
						), 
				'route' => array(
						array(
								'field' => 'name',
								'label' => 'Route Name',
								'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
							 )
						), 
                 'state' => array(
                                    array(
                                            'field' => 'name',
                                            'label' => 'State Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         )
                                    ),
                 'city' => array(
                                     array(
                                            'field' => 'state_name',
                                            'label' => 'State Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                   array(
                                            'field' => 'name',
                                            'label' => 'City Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         )
                                    ),

                 'delivery' => array(
                                   /*  array(
                                            'field' => 'state_name',
                                            'label' => 'State Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),*/
                                    array(
                                            'field' => 'city_name',
                                            'label' => 'City Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'address',
                                            'label' => 'Delivery Point Address',
                                            'rules' => 'trim|required|min_length[1]|max_length[250]|xss_clean'
                                         ),
                                    ),

                 'dishvendor' => array(
                                    array(
                                            'field' => 'item_type',
                                            'label' => 'Item Type',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'category_name',
                                            'label' => 'Category Name',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'item',
                                            'label' => 'Item Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'price',
                                            'label' => 'Price',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'description',
                                            'label' => 'Description',
                                            'rules' => 'trim|required|min_length[1]|max_length[250]|xss_clean'
                                         ),
                                    ),


                 'restaurant' => array(
                                    array(
                                            'field' => 'city_name',
                                            'label' => 'City Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'name',
                                            'label' => 'Restaurant Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'area',
                                            'label' => 'Restaurant Area',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'type',
                                            'label' => 'Restaurant Type',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'mobile',
                                            'label' => 'Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),
                                     array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email|xss_clean'
                                         ),
                                    array(
                                            'field' => 'address',
                                            'label' => 'Address',
                                            'rules' => 'trim|required|min_length[1]|max_length[250]|xss_clean'
                                         ),
                                     array(
                                            'field' => 'contact_name',
                                            'label' => 'Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                   array(
                                            'field' => 'contact_mobile',
                                            'label' => 'Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),
                                     array(
                                            'field' => 'contact_email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email|xss_clean'
                                         ),
                                   ),


                 'times' => array(
                                    array(
                                            'field' => 'breakfast_start_time',
                                            'label' => 'Break Fast Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'breakfast_end_time',
                                            'label' => 'Break Fast Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'lunch_start_time',
                                            'label' => 'Lunch Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'lunch_end_time',
                                            'label' => 'Lunch Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'snacks_start_time',
                                            'label' => 'Snacks Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'snacks_end_time',
                                            'label' => 'Break Fast Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'dinner_start_time',
                                            'label' => 'Dinner Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'dinner_end_time',
                                            'label' => 'Dinner Time',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'order_before_hours',
                                            'label' => 'Order Before Hours',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'cancel_before_hours',
                                            'label' => 'Cancel Before Hours',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    ),


                 'incharge-add' => array(
                                    array(
                                            'field' => 'city_name',
                                            'label' => 'City Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'name',
                                            'label' => 'Restaurant Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'mobile',
                                            'label' => 'Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),
                                     array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email|is_unique[tbl_incharges.email]|xss_clean'
                                         ),
                                     /*array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'trim|required|min_length[6]|max_length[50]|xss_clean'
                                         ),*/
                                  ),

                  'incharge-edit' => array(
                                    array(
                                            'field' => 'city_name',
                                            'label' => 'City Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'name',
                                            'label' => 'Restaurant Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'mobile',
                                            'label' => 'Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),
                                     array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email|xss_clean'
                                         ),
                                    /* array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'trim|required|min_length[6]|max_length[50]|xss_clean'
                                         ),*/
                                  ),
                'coupon' => array(
                                    array(
                                            'field' => 'coupon_name',
                                            'label' => 'Coupon Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[150]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'amount_type',
                                            'label' => 'Amount Type',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'coupon_amount',
                                            'label' => 'Coupon Amount',
                                            'rules' => 'trim|required|min_length[1]|max_length[10]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'coupon_num_off',
                                            'label' => 'No Of Usages',
                                            'rules' => 'trim|required|min_length[1]|max_length[20]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'from_date',
                                            'label' => 'From Date',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'to_date',
                                            'label' => 'To Date',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    ),
				 

                    
                 'homepageseo' => array(
                                    array(
                                            'field' => 'seo_title',
                                            'label' => 'SEO Title',
                                            'rules' => 'trim|required|min_length[20]|max_length[65]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'seo_description',
                                            'label' => 'SEO Description',
                                            'rules' => 'trim|required|min_length[20]|max_length[165]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'seo_keywords',
                                            'label' => 'SEO Keywords',
                                            'rules' => 'trim|required|min_length[20]|max_length[200]|xss_clean'
                                         )
                                    ),                          
                 'gateway' => array(
                                    array(
                                            'field' => 'gateway_name',
                                            'label' => 'Gateway Name',
                                            'rules' => 'trim|required|xss_clean'
                                         )
                                    ),                          
                 'contact' => array(
                                    array(
                                            'field' => 'name',
                                            'label' => 'Name',
                                            'rules' => 'trim|required|min_length[1]|max_length[50]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email|xss_clean'
                                         ),
                                    array(
                                            'field' => 'mobile',
                                            'label' => 'Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'message',
                                            'label' => 'Message',
                                            'rules' => 'trim|required|min_length[1]|max_length[250]|xss_clean'
                                         )
                                    ),    

									

//frontend server side validation end here
                 'loginform' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'Username',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'trim|required|xss_clean'
                                         )
                                    ),                          
                 'loginform-incharge' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'Username',
                                            'rules' => 'trim|required|valid_email|xss_clean'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'trim|required|xss_clean'
                                         )
                                    ),                          
                 'forgot_password' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email'
                                         )
                                    ),                          
 
                 'change_username' => array(
                                    array(
                                            'field' => 'cur_username',
                                            'label' => 'Current User Name',
                                            'rules' => 'trim|required|min_length[5]|max_length[30]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'new_username',
                                            'label' => 'New User Name',
                                            'rules' => 'trim|required|min_length[5]|max_length[30]|matches[conf_username]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'conf_username',
                                            'label' => 'Confirm User Name',
                                            'rules' => 'trim|required|min_length[5]|max_length[30]|matches[new_username]|xss_clean'
                                         )
                                    ),   
									
									 'change_password' => array(
                                    array(
                                            'field' => 'cur_pwd',
                                            'label' => 'Current Password',
                                            'rules' => 'trim|required|min_length[6]|max_length[30]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'new_pwd',
                                            'label' => 'New Password',
                                            'rules' => 'trim|required|min_length[6]|max_length[30]|matches[conf_pwd]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'conf_pwd',
                                            'label' => 'Confirm Password',
                                            'rules' => 'trim|required|min_length[6]|max_length[30]|matches[new_pwd]|xss_clean'
                                         )
                                    ),   
									
 
                 'content' => array(
                                    array(
                                            'field' => 'page_name',
                                            'label' => 'Page Name',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
                                    /* array(
                                            'field' => 'content',
                                            'label' => 'Content',
                                            'rules' => 'trim|required|xss_clean'
                                         )*/
                                   ), 
                 'banners' => array(
                                    array(
                                            'field' => 'image',
                                            'label' => 'Image',
                                            'rules' => 'trim|required|xss_clean'
                                         )
                                    ), 
                 'email_contact_details' => array(
                                    array(
                                            'field' => 'contact_email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email|xss_clean'
                                         ),
                                    array(
                                            'field' => 'contact_mobile',
                                            'label' => 'Mobile',
'rules' => 'trim|required|regex_match[/^[0-9 ()+-]+$/]|min_length[10]|max_length[14]|xss_clean'
                                         )
                                    ) , 
                 'mobile_email_details' => array(
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'trim|required|valid_email|xss_clean'
                                         ),
                                    array(
                                            'field' => 'mobile',
                                            'label' => 'Mobile',
                                            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'contactaddress',
                                            'label' => 'Contact Address',
                                            'rules' => 'trim|required|xss_clean'
                                         )
								   ),
                 'website_details' => array(
                                    array(
                                            'field' => 'host_name',
                                            'label' => 'Host Name',
                                            'rules' => 'trim|required|url|xss_clean'
                                         )
								   ),
                 'favicon_details' => array(
                                    array(
                                            'field' => 'favicon',
                                            'label' => 'Favicon',
                                            'rules' => 'trim|required|xss_clean'
                                         )
								   ),
				'socialmedia' => array(
                                    array(
                                            'field' => 'blog_url',
                                            'label' => 'Blog Url',
                                            'rules' => 'trim|xss_clean|prep_url|valid_url'
                                         ),  
									array(
                                            'field' => 'facebook_url',
                                            'label' => 'Facebook Url',   
                                            'rules' => 'trim|xss_clean'
                                         ),
									array(
                                            'field' => 'twitter_url',
                                            'label' => 'Twitter Url',
                                            'rules' => 'trim|xss_clean'
                                         ),
									array(
                                            'field' => 'linkedin_url',
                                            'label' => 'Linkedin Url',
                                            'rules' => 'trim|xss_clean'
                                         ),
									array(
                                            'field' => 'facebook_likebox_code',
                                            'label' => 'Facebook Likebox Code',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
									array(
                                            'field' => 'google_map_code',
                                            'label' => 'Google Map Code',
                                            'rules' => 'trim|required|xss_clean'
                                         )
                                    ),	   
									
				 'googlesettings' => array(
                                    array(
                                            'field' => 'google_map_api_code',
                                            'label' => 'Google Map Api Code',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
									array(
                                            'field' => 'google_analytic_code',
                                            'label' => 'Google Analytic Code',   
                                            'rules' => 'trim|required|xss_clean'
                                         ),
									array(
                                            'field' => 'google_adsense_pub_code',
                                            'label' => 'Google Adsense Pub Code',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
									array(
                                            'field' => 'display_adsense_ads',
                                            'label' => 'Display Adsense Ads',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
									array(
                                            'field' => 'google_site_verification_code',
                                            'label' => 'Google Site Verification Code',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
									array(
                                            'field' => 'yahoo_site_verification_key',
                                            'label' => 'Yahoo Site Verification Key',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
									 
									 
									 array(
                                            'field' => 'alexa_verification_code',
                                            'label' => 'Alexa Verification Code',
                                            'rules' => 'trim|required|xss_clean'
                                         ),
									array(
                                            'field' => 'msn_validate_code',
                                            'label' => 'msn Validate Code',
                                            'rules' => 'trim|required|xss_clean'
                                         )
                                    ),

						
			'sms_details'=>array(
						
						 array(
							 'field' => 'sender_id',
                             'label' => 'Sender Id',
                             'rules' => 'trim|required|xss_clean'),
							 
						 array(
							 'field' => 'username',
                             'label' => 'User Name',
                             'rules' => 'trim|required|xss_clean'),
						array(
							 'field' => 'password',
                             'label' => 'Password',
                             'rules' => 'trim|required|xss_clean'),
						),
						
		);    
/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */