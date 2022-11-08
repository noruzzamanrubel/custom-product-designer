<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * @package YITH Woocommerce Request A Quote
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Table view to Request A Quote
 *
 * @since   1.0.0
 * @author  YITH
 *
 * @package YITH Woocommerce Request A Quote
 * @version 3.0.0
 * @var  array $raq_content
 * @var string $show_thumbnail
 * @var string $show_sku
 * @var string $show_single_price
 * @var string $show_line_total
 * @var string $show_quantity
 * @var string $show_totals
 * @var string $shop_url
 * @var string $label_return_to_shop
 * @var string $shop_url_after_sent_email
 * @var string $label_return_to_shop_after_sent_email
 * @var string $show_back_to_shop
 * @var string $tax_display_list
 */

$colspan = 4;

$colspan = $show_line_total ? $colspan : $colspan - 1;
$colspan = $show_thumbnail ? $colspan : $colspan - 1;
$colspan = $show_single_price ? $colspan : $colspan - 1;

$mobile_colspan = $colspan;
$mobile_colspan = $show_line_total ? $mobile_colspan - 1 : $mobile_colspan;
$mobile_colspan = $show_single_price ? $mobile_colspan - 1 : $mobile_colspan;


$responsive_class = apply_filters( 'ywraq_responsive_table_class', 'ywraq_responsive' ); // old class shop_table_responsive.
$total_tax        = 0;
?>
<div class="ywraq-before-form">
	<?php
	$quote_id = isset( $_GET['quote_id'] ) ? sanitize_text_field( wp_unslash( $_GET['quote_id'] ) ) : false; //phpcs:ignore
	if ( $quote_id && wc_get_order( $quote_id ) ) :
		$shortcode = '[yith_ywraq_single_view_quote order_id="' . $quote_id . '"]';
		echo wp_kses_post( is_callable( 'apply_shortcodes' ) ? apply_shortcodes( $shortcode ) : do_shortcode( $shortcode ) );
		echo '</div>';

	elseif ( count( $raq_content ) === 0 ) :
		if ( empty( $notices ) || 0 === $notices ) {
			echo wp_kses_post( ywraq_get_list_empty_message() );
		} else {
			?>
			<a class="button "
			   href="<?php echo esc_url( $shop_url_after_sent_email ); ?>"><?php echo esc_html( $label_return_to_shop_after_sent_email ); ?></a>
			<?php
		}
		echo '</div>';
	else :
		?>

		<?php if ( $show_back_to_shop ) : ?>
		<a class="button return_to_shop_url"
		   href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html( $label_return_to_shop ); ?></a>
	<?php endif; ?>


		<?php

		if ( count( $raq_content ) !== 0 ) :
			echo '</div>';
			?>

			<form id="" name="" method="post">

				<?php do_action( 'ywraq_before_list_table' ); ?>

				<table class="shop_table cart  <?php echo esc_attr( $responsive_class ); ?>" id="yith-ywrq-table-list">
					<thead>
					<tr>
						<th class="product-remove"></th>
						<th class="product-name"
							colspan="<?php echo $show_thumbnail ? '2' : '1'; ?>"><?php esc_html_e( 'Product', 'yith-woocommerce-request-a-quote' ); ?></th>
						<?php if ( $show_single_price ) : ?>
							<th class="product-price"><?php esc_html_e( 'Price', 'yith-woocommerce-request-a-quote' ); ?></th>
						<?php endif ?>
						<?php if ( $show_quantity ) : ?>
							<th class="product-quantity"><?php esc_html_e( 'Quantity', 'yith-woocommerce-request-a-quote' ); ?></th>
						<?php endif; ?>
						<?php if ( $show_line_total ) : ?>
							<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'yith-woocommerce-request-a-quote' ); ?></th>
						<?php endif ?>
					</tr>
					</thead>
					<tbody>
					<?php
					$total        = 0;
					$total_exc    = 0;
					$total_inc    = 0;
					foreach ( $raq_content as $key => $raq ) :
						$product_id = ( ! empty( $raq['variation_id'] ) && $raq['variation_id'] > 0 ) ? $raq['variation_id'] : $raq['product_id'];
						$_product = wc_get_product( $product_id );

						if ( ! $_product ) {
							continue;
						}

						// Wishlist integration.
						if ( class_exists( 'YITH_WCWL' ) && empty( $raq['variation_id'] ) ) {

							$variation  = wc_get_product( $raq['product_id'] );
							$attributes = $variation->get_attributes();
							$raq_data   = array();

							if ( ! empty( $attributes ) ) {
								foreach ( $attributes as $name => $value ) {
									$raq_data[ 'attribute_' . $name ] = $value;
								}
							}

							$raq['variation_id'] = $raq['product_id'];
							$raq['variations']   = $raq_data;
						}

						$show_price = true;
						do_action( 'ywraq_quote_adjust_price', $raq, $_product );
						$price = ( 'incl' === $tax_display_list ) ? wc_get_price_including_tax( $_product, array( 'qty' => 1 ) ) : wc_get_price_excluding_tax( $_product, array( 'qty' => 1 ) );
						if ( $price ) {
							$price_with_tax    = wc_get_price_including_tax( $_product, array( 'qty' => 1 ) );
							$price_without_tax = wc_get_price_excluding_tax( $_product, array( 'qty' => 1 ) );
							$price             = isset( WC()->cart ) ? apply_filters( 'yith_ywraq_product_price_html', WC()->cart->get_product_subtotal( $_product, 1 ), $_product, $raq ) : $_product->get_price();
						} else {
							$price = wc_price( 0 );
						}

						do_action( 'ywraq_before_request_quote_view_item', $raq_content, $key );

						if ( ! empty( $raq['yith_wapo_individual_item'] ) && 1 === $raq['yith_wapo_individual_item'] && ! empty( $raq['yith_wapo_parent_key'] ) ) :
							?>
							<tr class="<?php echo esc_attr( apply_filters( 'yith_ywraq_item_class', 'cart_item', $raq_content, $key ) ); ?>"
								data-wapo_parent_key="<?php echo esc_attr( $raq['yith_wapo_parent_key'] ); ?>" <?php echo esc_attr( apply_filters( 'yith_ywraq_item_attributes', '', $raq_content, $key ) ); ?>>

								<td class="product-remove"></td>
								<?php if ( $show_thumbnail ) : ?>
									<td class="product-thumbnail"></td>
								<?php endif; ?>

								<td class="product-name"
									data-title="<?php esc_attr_e( 'Product', 'yith-woocommerce-request-a-quote' ); ?>">
									<?php
									// Meta data.
									$item_data = array();

									foreach ( $raq['yith_wapo_options'] as $individual_item ) {
										$individual_wapo_item_price = '';
										if ( $show_price && $individual_item['price'] > 0 && 'yes' === get_option( 'ywraq_hide_price' ) ) {
											$individual_wapo_item_price = ' ( +' . wp_strip_all_tags( wc_price( $individual_item['price'] ) ) . ' ) ';
										}

										if ( class_exists( 'YITH_WAPO_WPML' ) ) {
											$key = YITH_WAPO_WPML::string_translate( $individual_item['name'] );
											if ( strpos( $individual_item['value'], 'Attached file' ) ) {
												$array = new SimpleXMLElement( $individual_item['value'] );
												$link  = $array['href']; //phpcs:ignore
												$value = '<a href="' . esc_url( $link ) . '" target="_blank">' . esc_html_x( 'Attached file', 'Integration: product add-ons attachment', 'yith-woocommerce-request-a-quote' ) . '</a>';
											} else {
												$value = YITH_WAPO_WPML::string_translate( $individual_item['value'] );
											}
										} else {
											$key   = $individual_item['name'];
											$value = $individual_item['value'];
										}

										$item_data[] = array(
											'key'   => $key . $individual_wapo_item_price,
											'value' => urldecode( $value ),
										);
									}

									// Output flat or in list format.
									if ( count( $item_data ) > 0 ) {
										echo '<ul style="margin-left: 10px;">';
										foreach ( $item_data as $data ) {
											echo '<li><strong>' . esc_html( $data['key'] ) . '</strong>: ' . wp_kses_post( $data['value'] ) . '</li><br>';
										}
										echo '</ul>';
									}
									?>
									<?php if ( $show_line_total || $show_single_price ) : ?>
										<span
											class="mobile-price"><?php echo wp_kses_post( $show_single_price ? $price : $subtotal ); ?></span>
									<?php endif; ?>
								</td>
								<?php if ( $show_single_price ) : ?>
									<td class="product-price"
										data-title="<?php esc_attr_e( 'Price', 'yith-woocommerce-request-a-quote' ); ?>">
										<?php
										echo wp_kses_post( apply_filters( 'yith_ywraq_hide_price_template', $price, $product_id, $raq ) );
										?>
									</td>
								<?php endif; ?>
								<?php if ( $show_quantity ) : ?>
									<td class="product-quantity"
										data-title="<?php esc_attr_e( 'Quantity', 'yith-woocommerce-request-a-quote' ); ?>">
										<?php echo esc_html( $raq['quantity'] ); ?>
									</td>
								<?php endif; ?>
								<?php if ( $show_line_total ) : ?>
									<td class="product-subtotal"
										data-title="<?php esc_attr_e( 'Subtotal', 'yith-woocommerce-request-a-quote' ); ?>">
										<?php
										echo wp_kses_post( apply_filters( 'yith_ywraq_hide_price_template', $subtotal, $product_id, $raq ) );
										?>
									</td>
								<?php endif; ?>
							</tr>
						<?php

						else :
							$price = ( 'incl' === $tax_display_list ) ? wc_get_price_including_tax( $_product, array( 'qty' => $raq['quantity'] ) ) : wc_get_price_excluding_tax( $_product, array( 'qty' => $raq['quantity'] ) );

							if ( $price ) {
								if ( $_product->is_type( 'yith-composite' ) && isset( $raq['yith_wcp_component_data'] ) ) {
									$component_data                   = $raq['yith_wcp_component_data'];
									$composite_stored_data            = $_product->getComponentsData();
									$composite_data_price             = 0;
									$composite_data_price_with_tax    = 0;
									$composite_data_price_without_tax = 0;
									foreach ( $composite_stored_data as $wcp_key => $component_item ) {
										if ( isset( $component_data['selection_data'][ $wcp_key ] ) && $component_data['selection_data'][ $wcp_key ] > 0 ) {
											// variation selected.
											if ( isset( $component_data['selection_variation_data'][ $wcp_key ] ) && $component_data['selection_variation_data'][ $wcp_key ] > 0 ) {
												$child_product = wc_get_product( $component_data['selection_variation_data'][ $wcp_key ] );
											} else {
												$child_product = wc_get_product( $component_data['selection_data'][ $wcp_key ] );
											}
											if ( ! $child_product ) {
												continue;
											}
											$composite_data_price             += wc_get_price_to_display( $child_product, array( 'qty' => $raq['quantity'] ) );
											$composite_data_price_with_tax    += wc_get_price_including_tax( $child_product, array( 'qty' => $raq['quantity'] ) );
											$composite_data_price_without_tax += wc_get_price_excluding_tax( $child_product, array( 'qty' => $raq['quantity'] ) );
										}
									}
									$total     += floatval( $price + $composite_data_price );
									$total_tax += floatval( $composite_data_price_with_tax - $composite_data_price_without_tax );
								} else {
									$price_with_tax    = wc_get_price_including_tax( $_product, array( 'qty' => $raq['quantity'] ) );
									$price_without_tax = wc_get_price_excluding_tax( $_product, array( 'qty' => $raq['quantity'] ) );
									$total             += floatval( $price );
									$total_tax         += floatval( $price_with_tax - $price_without_tax );
								}


								$price    = isset( WC()->cart ) ? WC()->cart->get_product_subtotal( $_product, 1 ) : wc_get_price_to_display( $_product, 1 );
								$price    = apply_filters( 'yith_ywraq_product_price_html', $price, $_product, $raq );
								$subtotal = isset( WC()->cart ) ? WC()->cart->get_product_subtotal( $_product, $raq['quantity'] ) : wc_get_price_to_display( $_product, array( 'qty' => $raq['quantity'] ) );
								$subtotal = apply_filters( 'yith_ywraq_product_subtotal_html', $subtotal, $_product, $raq );

							} else {
								$price    = wc_price( 0 );
								$subtotal = wc_price( 0 );
							}

                            // Meta data.
                            $item_data = array();

                            // Variation data.
                            if (!empty($raq['variation_id']) && is_array($raq['variations'])) {

                                foreach ($raq['variations'] as $name => $value) {
                                    $label = '';

                                    if ('' === $value || is_a($value, 'WC_Product_Attribute')) {
                                        continue;
                                    }

                                    $taxonomy = wc_attribute_taxonomy_name(str_replace('attribute_pa_', '', urldecode($name))); //phpcs:ignore

                                    // If this is a term slug, get the term's nice name.
                                    if (taxonomy_exists($taxonomy)) {
                                        $term = get_term_by('slug', $value, $taxonomy); //phpcs:ignore
                                        if (!is_wp_error($term) && $term && $term->name) {
                                            $value = $term->name;
                                        }
                                        $label = wc_attribute_label($taxonomy);

                                    } else {

                                        if (strpos($name, 'attribute_') !== false) {
                                            $custom_att = str_replace('attribute_', '', $name);

                                            if ('' !== $custom_att) {
                                                $label = wc_attribute_label($custom_att, $_product);
                                            } else {
                                                $label = $name;
                                            }
                                        }
                                    }

                                    $item_data[] = array(
                                        'key' => $label,
                                        'value' => $value,
                                    );
                                }
                            }

                            $item_data = apply_filters('ywraq_request_quote_view_item_data', $item_data, $raq, $_product, $show_price);

							?>
							<tr class="<?php echo esc_attr( apply_filters( 'yith_ywraq_item_class', 'cart_item', $raq_content, $key ) ); ?>" <?php echo esc_attr( apply_filters( 'yith_ywraq_item_attributes', '', $raq_content, $key ) ); ?>>

								<td class="product-remove">
									<?php
									echo apply_filters( 'yith_ywraq_item_remove_link', sprintf( '<a href="#"  data-remove-item="%s" data-wp_nonce="%s"  data-product_id="%d" class="yith-ywraq-item-remove remove" title="%s">&times;</a>', esc_attr( $key ), esc_attr( wp_create_nonce( 'remove-request-quote-' . $product_id ) ), esc_attr( $product_id ), esc_attr__( 'Remove this item', 'yith-woocommerce-request-a-quote' ) ), $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</td>
								<?php if ( $show_thumbnail ) : ?>
									<td class="product-thumbnail">
                                        <div class="custom_cart_design" style="position: relative; display: inline-block">
                                            <?php
                                            /**
                                             * APPLY_FILTERS:ywraq_product_image
                                             *
                                             * Filter the image of product.
                                             *
                                             * @param   string  $product_image  Product image.
                                             * @param   array   $raq            Quote request content.
                                             *
                                             * @return array
                                             */
                                            $thumbnail = apply_filters( 'ywraq_product_image', $_product->get_image(), $raq );

                                            if ( count( $item_data ) > 0 ) {
                                                global $wpdb;
                                                foreach ( $item_data as $data ) {
                                                    $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type= %s", $data['value'], 'fanclubs-design'));
                                                    $meta = get_post_meta( intval($post), 'fan_clubs', true );
                                                    $fanclubs_upload_img = $meta['fanclubs_upload_img'];
                                                    $fanclubs_design_page = $meta['fanclubs_design_page'];
                                                    $fanclubs_design_page_width = $fanclubs_design_page['fanclubs_design_page_width'];
                                                    $fanclubs_design_page_top = $fanclubs_design_page['fanclubs_design_page_top'];
                                                    $fanclubs_design_page_left = $fanclubs_design_page['fanclubs_design_page_left'];

                                                    $product_meta = $meta['fanclubs_select_product'];
                                                    for($i=0; $i < count($product_meta); $i++) {
                                                        $fanclubs_product_upload_img = $product_meta[$i]['fanclubs_product_upload_img'];
                                                        $fanclubs_custom_design_page = $product_meta[$i]['fanclubs_custom_design_page'];
                                                        $fanclubs_product_custom_checkbox = $fanclubs_custom_design_page['fanclubs_product_custom_checkbox'];
                                                        $fanclubs_custom_design_page_width = $fanclubs_custom_design_page['fanclubs_custom_design_page_width'];
                                                        $fanclubs_custom_design_page_top = $fanclubs_custom_design_page['fanclubs_custom_design_page_top'];
                                                        $fanclubs_custom_design_page_left = $fanclubs_custom_design_page['fanclubs_custom_design_page_left'];

                                                        if(intval($product_meta[$i]['fanclubs_product']) === $_product->get_parent_id()) {
                                                            if($fanclubs_product_custom_checkbox == true){
                                                                if($fanclubs_product_upload_img !== '') {
                                                                    ?>
                                                                    <img src="<?php echo $fanclubs_product_upload_img; ?>" style="position: absolute; top: <?php echo $fanclubs_custom_design_page_top; ?>%; left: <?php echo $fanclubs_custom_design_page_left; ?>%; width: <?php echo $fanclubs_custom_design_page_width;?>%;">
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <img src="<?php echo $fanclubs_upload_img; ?>" style="position: absolute; top: <?php echo $fanclubs_custom_design_page_top; ?>%; left: <?php echo $fanclubs_custom_design_page_left; ?>%; width: <?php echo $fanclubs_custom_design_page_width;?>%;">
                                                                    <?php
                                                                }
                                                            }else {
                                                                if($fanclubs_product_upload_img !== '') {
                                                                    ?>
                                                                    <img src="<?php echo $fanclubs_product_upload_img;  ?>" style="position: absolute; top: <?php echo $fanclubs_design_page_top; ?>%; left: <?php echo $fanclubs_design_page_left; ?>%; width: <?php echo $fanclubs_design_page_width;?>%;">
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <img src="<?php echo $fanclubs_upload_img; ?>" style="position: absolute; top: <?php echo $fanclubs_design_page_top; ?>%; left: <?php echo $fanclubs_design_page_left; ?>%; width: <?php echo $fanclubs_design_page_width;?>%;">
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            if ( ! $_product->is_visible() || ! apply_filters( 'ywraq_list_show_product_permalinks', true, 'quote-view' ) ) {
                                                echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            } else {
                                                printf( '<a href="%s">%s</a>', esc_url( $_product->get_permalink() ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            }
                                            ?>
                                        </div>
									</td>
								<?php endif; ?>

								<td class="product-name"
									data-title="<?php esc_attr_e( 'Product', 'yith-woocommerce-request-a-quote' ); ?>">
									<?php
									$title = $_product->get_title(); //phpcs:ignore

									if ( '' !== $_product->get_sku() && $show_sku ) {
										$sku_label = apply_filters( 'ywraq_sku_label', __( ' SKU:', 'yith-woocommerce-request-a-quote' ) );
										$sku       = sprintf( '<br><small>%s %s</small>', $sku_label, $_product->get_sku() );
										$title     .= apply_filters( 'ywraq_sku_label_html', $sku, $_product ); //phpcs:ignore
									}

									if ( ! $_product->is_visible() || ! apply_filters( 'ywraq_list_show_product_permalinks', true, 'quote-view' ) ) :
										?>
										<?php echo wp_kses_post( apply_filters( 'ywraq_quote_item_name', $title, $raq, $key ) ); ?>
									<?php else : ?>
										<a href="<?php echo esc_url( $_product->get_permalink() ); ?>"><?php echo wp_kses_post( apply_filters( 'ywraq_quote_item_name', $title, $raq, $key ) ); ?></a>
									<?php endif ?>

                                    <?php
									// Output flat or in list format.
									if ( count( $item_data ) > 0 ) {
										echo '<br><ul style="margin-left: 10px;">';
										foreach ( $item_data as $data ) {
											echo '<li><strong>' . esc_html( $data['key'] ) . '</strong>: ' . wp_kses_post( $data['value'] ) . '</li><br>';
										}
										echo '</ul>';
									}

									?>
									<?php if ( $show_line_total || $show_single_price ) : ?>
										<span class="mobile-price">
											<?php
											$mobile_price = ( $show_single_price ? $price : $subtotal );
											echo wp_kses_post( apply_filters( 'yith_ywraq_hide_price_template', $mobile_price, $product_id, $raq ) );
											?>
											</span>

									<?php endif; ?>
								</td>

								<?php if ( $show_single_price ) : ?>
									<td class="product-price"
										data-title="<?php esc_attr_e( 'Price', 'yith-woocommerce-request-a-quote' ); ?>">
										<?php
										echo wp_kses_post( apply_filters( 'yith_ywraq_hide_price_template', $price, $product_id, $raq ) );
										?>
									</td>
								<?php endif; ?>

								<?php if ( $show_quantity ) : ?>
									<td class="product-quantity"
										data-title="<?php esc_attr_e( 'Quantity', 'yith-woocommerce-request-a-quote' ); ?>">
										<?php

										if ( $_product->is_sold_individually() ) {
											$product_quantity = sprintf( '1 <input type="hidden" name="raq[%s][qty]" value="1" />', $key );
										} else {

											$product_quantity = woocommerce_quantity_input(
												array(
													'input_name'  => "raq[{$key}][qty]",
													'input_value' => apply_filters( 'ywraq_quantity_input_value', $raq['quantity'] ),
													'max_value'   => apply_filters( 'ywraq_quantity_max_value', $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(), $_product ),
													'min_value'   => apply_filters( 'ywraq_quantity_min_value', 0, $_product ),
													'step'        => apply_filters( 'ywraq_quantity_step_value', 1, $_product ),
												),
												$_product,
												false
											);

										}

										echo $product_quantity; //@phpcs:ignore
										?>
									</td>
								<?php endif; ?>

								<?php if ( $show_line_total ) : ?>
									<td class="product-subtotal"
										data-title="<?php esc_attr_e( 'Price', 'yith-woocommerce-request-a-quote' ); ?>">
										<?php echo wp_kses_post( apply_filters( 'yith_ywraq_hide_price_template', $subtotal, $product_id, $raq ) ); ?>
									</td>
								<?php endif ?>
							</tr>
						<?php endif; ?>

						<?php do_action( 'ywraq_after_request_quote_view_item', $raq_content, $key ); ?>

					<?php endforeach ?>
					<?php if ( $show_totals ) : ?>
						<?php
						if ( $total_tax > 0 && 'incl' !== $tax_display_list && ywraq_show_taxes_on_quote_list() ) :
							$total += $total_tax;
							?>
							<tr>
								<td class="raq-totals-row" colspan="<?php echo esc_attr( $colspan ); ?>"
									data-colspan-mobile="<?php echo esc_attr( $mobile_colspan ); ?>"></td>
								<th class="raq-totals-label">
									<?php echo esc_html( WC()->countries->tax_or_vat() ); ?>
								</th>
								<td class="raq-totals">
									<?php echo wp_kses_post( wc_price( $total_tax ) ); ?>
								</td>
							</tr>
						<?php endif; ?>
						<tr>
							<th colspan="<?php echo esc_attr( $colspan ); ?>"
								data-colspan-mobile="<?php echo esc_attr( $mobile_colspan ); ?>"
								data-colspan="<?php echo esc_attr( $colspan ); ?>"
								class="raq-totals-row"></th>
							<th class="raq-totals-label">
								<?php esc_html_e( 'Total:', 'yith-woocommerce-request-a-quote' ); ?>
							</th>
							<td class="raq-totals"
								data-title="<?php esc_attr_e( 'Total', 'yith-woocommerce-request-a-quote' ); ?>">
								<?php
								echo wp_kses_post( wc_price( $total ) );
								if ( $total_tax > 0 && 'incl' === $tax_display_list && ywraq_show_taxes_on_quote_list() ) {
									echo wp_kses_post( '<br><small class="includes_tax">' . sprintf( '%1$s %2$s %3$s', __( 'includes', 'yith-woocommerce-request-a-quote' ), wp_kses_post( wc_price( $total_tax ) ), wp_kses_post( WC()->countries->tax_or_vat() ) ) . '</small>' );
								}
								?>
							</td>
						</tr>
					<?php endif; ?>

					</tbody>
				</table>
				<?php
				$show_pdf_button         = get_option( 'ywraq_show_download_pdf_on_request', 'no' ) === 'yes';
				$show_clear_list         = get_option( 'ywraq_show_clear_list_button', 'yes' ) === 'yes';
				$style                   = $show_clear_list || $show_pdf_button ? 'justify-content:space-between' : 'justify-content:end';
				$show_update_list_button = get_option( 'ywraq_show_update_list', 'yes' ) === 'yes';


				if ( $show_pdf_button || $show_update_list_button || $show_clear_list ) : ?>
					<div class="update-list-wrapper" style="<?php echo esc_attr( $style ); ?>">
						<div class="after-table-right">
							<?php if ( $show_clear_list ): ?>
								<button class="button ywraq_clean_list"> <?php echo apply_filters( 'ywraq_clear_list_label', esc_html__( 'Clear List', 'yith-woocommerce-request-a-quote' ) ); ?> </button>
							<?php endif; ?>
							<?php if ( $show_pdf_button ): ?>
								<button id="ywraq-list-to-pdf" class="button button-ghost" data-nonce="<?php echo esc_attr( wp_create_nonce( 'ywraq-list-to-pdf' ) ); ?>">
									<?php echo esc_html( get_option( 'ywraq_show_download_pdf_on_request_label', _x( 'PDF', 'Admin option label for button to make a PDF on Request a quote page', 'yith-woocommerce-request-a-quote' ) ) ); ?></button>
							<?php endif; ?>
						</div>
						<?php if ( $show_update_list_button ): ?>
							<input type="submit" class="button" name="update_raq" value="<?php echo esc_attr( get_option( 'ywraq_update_list_label' ) ); ?>">
							<input type="hidden" id="update_raq_wpnonce" name="update_raq_wpnonce"
								   value="<?php echo esc_attr( wp_create_nonce( 'update-request-quote-quantity' ) ); ?>">
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php do_action( 'ywraq_after_list_table' ); ?>
			</form>
		<?php endif; ?>
	<?php endif; ?>
