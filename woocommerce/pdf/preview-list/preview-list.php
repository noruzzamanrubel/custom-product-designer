<?php
/**
 * Preview list on quote page
 *
 * @package YITH Woocommerce Request A Quote
 * @since   4.0.0
 * @version 4.0.0
 * @author  YITH
 */

defined( 'ABSPATH' ) || exit;

$logo_url           = get_option( 'ywraq_pdf_logo' );
$logo_attachment_id = apply_filters( 'yith_pdf_logo_id', get_option( 'ywraq_pdf_logo-yith-attachment-id' ) );
if ( ! $logo_attachment_id && $logo_url ) {
	$logo_attachment_id = attachment_url_to_postid( $logo_url );
}
$logo       = $logo_attachment_id ? get_attached_file( $logo_attachment_id ) : $logo_url;
$image_type = wp_check_filetype( $logo );
$mime_type  = array( 'image/jpeg', 'image/png' );
$logo       = apply_filters(
	'ywraq_pdf_logo',
	( isset( $image_type['type'] ) && in_array( $image_type['type'], $mime_type, true ) ) ? $logo : ''
);
$colspan    = 1;

$raq_content       = YITH_Request_Quote()->get_raq_return();
$tax_display_list  = apply_filters( 'ywraq_tax_display_list', get_option( 'woocommerce_tax_display_cart' ) );
$total_tax         = 0;
$show_sku          = ywraq_show_element_on_list( 'sku' );
$show_quantity     = ywraq_show_element_on_list( 'quantity' );
$show_line_total   = ywraq_show_element_on_list( 'line_total' );
$show_single_price = ywraq_show_element_on_list( 'single_price' );
$show_thumbnail    = ywraq_show_element_on_list( 'images' );
$show_totals       = ywraq_show_element_on_list( 'total' );
$document_title    = apply_filters( 'ywpar_pdf_preview_list_title', __( 'Products in your quote list:', 'yith-woocommerce-request-a-quote' ) );
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<style type="text/css">
		body {
			color: #000;
		}

		.logo {
			width: 100%;
			float: left;
			max-width: 300px;
		}

		.separator{
			border-bottom: 1px solid #d7d7d7;
			height: 1px;
			margin-bottom: 50px;
		}

		h2{
			font-size:16px;
			margin-bottom: 50px;
		}

		table {
			border: 0;
		}

		table.quote-table {
			border: 0;
			font-size: 12px;
		}
		a{
			text-decoration: none;
			color:#000;
		}
		a:hover{
			color:#000;
		}
		table.quote-table th{
			font-size: 12px;
		}

		.quote-table td {
			border: 0;
			border-bottom: 1px solid #eee;
		}

		.quote-table td.number {
			text-align: right;
		}

		.quote-table .with-border td {
			border-bottom: 2px solid #eee;
		}

		.quote-table .with-border td {
			border-top: 2px solid #eee;
		}

		.quote-table .quote-total td {
			height: 100px;
			vertical-align: middle;
			font-size: 15px;
			border-bottom: 0;
		}

		.quote-table small {
			font-size: 11px;
		}

		.quote-table .tot {
			font-weight: 600;
		}
        .quote-table th{
            height: 40px;
        }

        .quote-table .tot th{
            height: 60px;
        }
        .product-name{
            line-height: 1.6em;
            text-align: left;
        }
        .wc-item-meta{
            font-size
        }
       img.thumbnail-img {
            width: 50px!important;
            display: inline-block;
        }

	</style>
</head>

<body>
<div class="logo">
    <img src="<?php echo apply_filters( 'ywraq_downloar_pdf_logo_src', $logo ); //phpcs:ignore ?>"  style="max-width: 300px;">
</div>
<div class="separator"></div>
<h2><?php echo esc_html( $document_title ); ?></h2>
<div class="table-wrapper">
	<table class="quote-table" cellspacing="0" cellpadding="6" style="width: 100%;" border="0" width="100%">
		<thead>
		<tr>
			<?php
			if ( $show_thumbnail ) {
				++ $colspan;
			}
			?>
			<th scope="col" colspan="<?php echo esc_attr( $colspan ); ?>"
				style="text-align:left; border-bottom: 1px solid #eee;" cellspacing="20px">
				<?php esc_html_e( 'Product', 'yith-woocommerce-request-a-quote' ); ?>
				<?php ++ $colspan; ?>
			</th>
			<?php if ( $show_quantity ) : ?>
				<th scope="col" style="text-align:right; border-bottom: 1px solid #eee;">
					<?php esc_html_e( 'Quantity', 'yith-woocommerce-request-a-quote' ); ?>
					<?php ++ $colspan; ?>
				</th>
			<?php endif ?>
			<?php if ( $show_single_price ) : ?>
				<th scope="col" style="text-align:right; border-bottom: 1px solid #eee;">
					<?php esc_html_e( 'Unit Price', 'yith-woocommerce-request-a-quote' ); ?>
					<?php ++ $colspan; ?>
				</th>
			<?php endif ?>
			<?php if ( $show_line_total ) : ?>
				<th scope="col" class="last-col number" style="border-bottom: 1px solid #eee;text-align: right">
					<?php esc_html_e( 'Subtotal', 'yith-woocommerce-request-a-quote' ); ?>
					<?php ++ $colspan; ?>
				</th>
			<?php endif ?>
		</tr>
		</thead>
		<tbody>
		<?php
		$total     = 0;
		$total_exc = 0;
		$total_inc = 0;
		foreach ( $raq_content as $key => $raq ) :
			$product_id = ( ! empty( $raq['variation_id'] ) && $raq['variation_id'] > 0 ) ? $raq['variation_id'] : $raq['product_id'];
			$_product   = wc_get_product( $product_id );

			if ( ! $_product ) {
				continue;
			}

			$show_price = true;
			do_action( 'ywraq_quote_adjust_price', $raq, $_product );
			$price = ( 'incl' === $tax_display_list ) ? wc_get_price_including_tax( $_product, array( 'qty' => 1 ) ) : wc_get_price_excluding_tax( $_product, array( 'qty' => 1 ) );
			if ( $price ) {
				$price_with_tax    = wc_get_price_including_tax( $_product, array( 'qty' => 1 ) );
				$price_without_tax = wc_get_price_excluding_tax( $_product, array( 'qty' => 1 ) );
				$price             = isset( WC()->cart ) ? apply_filters( 'yith_ywraq_product_price_html', WC()->cart->get_product_subtotal( $_product, 1 ), $_product, $raq ) : $_product->get_height();
			} else {
				$price = wc_price( 0 );
			}

			do_action( 'ywraq_before_request_quote_view_item', $raq_content, $key );

			if ( ! empty( $raq['yith_wapo_individual_item'] ) && 1 === $raq['yith_wapo_individual_item'] && ! empty( $raq['yith_wapo_parent_key'] ) ) :
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'yith_ywraq_item_class', 'cart_item', $raq_content, $key ) ); ?>"
					 <?php echo esc_attr( apply_filters( 'yith_ywraq_item_attributes', '', $raq_content, $key ) ); ?>>
					<?php if ( $show_thumbnail ) : ?>
						<td class="product-thumbnail"></td>
					<?php endif; ?>

					<td class="product-name" >
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
					</td>
					<?php if ( $show_single_price ) : ?>
						<td class="product-price">
							<?php
							echo wp_kses_post( apply_filters( 'yith_ywraq_hide_price_template', $price, $product_id, $raq ) );
							?>
						</td>
					<?php endif; ?>
					<?php if ( $show_quantity ) : ?>
						<td class="product-quantity">
							<?php echo esc_html( $raq['quantity'] ); ?>
						</td>
					<?php endif; ?>
					<?php if ( $show_line_total ) : ?>
						<td class="product-subtotal">
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
						$total            += floatval( $price );
						$total_tax        += floatval( $price_with_tax - $price_without_tax );
					}


					$price    = isset( WC()->cart ) ? WC()->cart->get_product_subtotal( $_product, 1 ) : wc_get_price_to_display( $_product, 1 );
					$price    = apply_filters( 'yith_ywraq_product_price_html', $price, $_product, $raq );
					$subtotal = isset( WC()->cart ) ? WC()->cart->get_product_subtotal( $_product, $raq['quantity'] ) : wc_get_price_to_display( $_product, array( 'qty' => $raq['quantity'] ) );
					$subtotal = apply_filters( 'yith_ywraq_product_subtotal_html', $subtotal, $_product, $raq );

				} else {
					$price    = wc_price( 0 );
					$subtotal = wc_price( 0 );
				}


				?>
				<tr class="<?php echo esc_attr( apply_filters( 'yith_ywraq_item_class', 'cart_item', $raq_content, $key ) ); ?>" <?php echo esc_attr( apply_filters( 'yith_ywraq_item_attributes', '', $raq_content, $key ) ); ?>>
					<?php if ( $show_thumbnail ) : ?>
						<td class="product-thumbnail" width="80px">
							<?php
							$image_id = $_product->get_image_id();
							if ( $image_id ) {
								$thumbnail_id  = $image_id;
								$thumbnail_url = apply_filters( 'ywraq_pdf_product_thumbnail', get_attached_file( $thumbnail_id ), $thumbnail_id );
							} else {
								$thumbnail_url = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : '';
							}

							$thumbnail = sprintf( '<img src="%s" class="thumbnail-img"/>', $thumbnail_url );

							if ( ! $_product->is_visible() ) {
								echo $thumbnail; //phpcs:ignore
							} else {
								printf( '<a href="#">%s</a>', $thumbnail ); //phpcs:ignore
							}
							?>
						</td>
					<?php endif; ?>

					<td class="product-name">
						<?php
						$title = $_product->get_title(); //phpcs:ignore

						if ( '' !== $_product->get_sku() && $show_sku ) {
							$sku_label = apply_filters( 'ywraq_sku_label', __( ' SKU:', 'yith-woocommerce-request-a-quote' ) );
							$sku       = sprintf( '<br><small>%s %s</small>', $sku_label, $_product->get_sku() );
							$title .=  apply_filters( 'ywraq_sku_label_html', $sku, $_product ); //phpcs:ignore
						}

						if ( ! $_product->is_visible() || ! apply_filters( 'ywraq_list_show_product_permalinks', true, 'quote-view' ) ) :
							?>
							<?php echo wp_kses_post( apply_filters( 'ywraq_quote_item_name', $title, $raq, $key ) ); ?>
						<?php else : ?>
							<a href="#"><?php echo wp_kses_post( apply_filters( 'ywraq_quote_item_name', $title, $raq, $key ) ); ?></a>
						<?php endif ?>

						<?php
						// Meta data.
						$item_data = array();

						// Variation data.
						if ( ! empty( $raq['variation_id'] ) && is_array( $raq['variations'] ) ) {

							foreach ( $raq['variations'] as $name => $value ) {
								$label = '';

								if ( '' === $value ) {
									continue;
								}

								$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) ); //phpcs:ignore

								// If this is a term slug, get the term's nice name.
								if ( taxonomy_exists( $taxonomy ) ) {
									$term = get_term_by( 'slug', $value, $taxonomy ); //phpcs:ignore
									if ( ! is_wp_error( $term ) && $term && $term->name ) {
										$value = $term->name;
									}
									$label = wc_attribute_label( $taxonomy );

								} else {

									if ( strpos( $name, 'attribute_' ) !== false ) {
										$custom_att = str_replace( 'attribute_', '', $name );

										if ( '' !== $custom_att ) {
											$label = wc_attribute_label( $custom_att, $_product );
										} else {
											$label = $name;
										}
									}
								}

								$item_data[] = array(
									'key'   => $label,
									'value' => $value,
								);
							}
						}

						$item_data = apply_filters( 'ywraq_request_quote_view_item_data', $item_data, $raq, $_product, $show_price );


						// Output flat or in list format.
						if ( count( $item_data ) > 0 ) {
						//	echo '<div class="wc-item-meta"><span>';
							foreach ( $item_data as $data ) {
								echo '<div class="wc-item-meta"><strong>' . esc_html( $data['key'] ) . '</strong>: ' . wp_kses_post( $data['value'] ) . '</div>';
							}
						//	echo '</ul>';
						}
						?>
					</td>

					<?php if ( $show_quantity ) : ?>
						<td class="product-quantity number">
							<?php echo $raq['quantity'] ; //@phpcs:ignore ?>
						</td>
					<?php endif; ?>
					<?php if ( $show_single_price ) : ?>
						<td class="product-price number">
							<?php
							echo wp_kses_post( apply_filters( 'yith_ywraq_hide_price_template', $price, $product_id, $raq ) );
							?>
						</td>
					<?php endif; ?>
					<?php if ( $show_line_total ) : ?>
						<td class="product-subtotal number">
							<?php echo wp_kses_post( apply_filters( 'yith_ywraq_hide_price_template', $subtotal, $product_id, $raq ) ); ?>
						</td>
					<?php endif ?>
				</tr>
			<?php endif; ?>

			<?php do_action( 'ywraq_after_request_quote_view_item', $raq_content, $key ); ?>

		<?php endforeach ?>
		<?php
		if ( $show_totals ) :
			$colspan -= 3;
			?>
			<?php
			if ( $total_tax > 0 && 'incl' !== $tax_display_list && ywraq_show_taxes_on_quote_list() ) :
				$total += $total_tax;

				?>
				<tr>
					<td colspan="<?php echo esc_attr( $colspan ); ?>"></td>
					<th class="raq-totals-label">
						<?php echo esc_html( WC()->countries->tax_or_vat() ); ?>
					</th>
					<td class="raq-totals number">
						<?php echo wp_kses_post( wc_price( $total_tax ) ); ?>
					</td>
				</tr>
			<?php endif; ?>
			<tr class="tot" >
				<th colspan="<?php echo esc_attr( $colspan ); ?>"></th>
				<th class="raq-totals-label">
					<?php esc_html_e( 'Total:', 'yith-woocommerce-request-a-quote' ); ?>
				</th>
				<td class="raq-totals number">
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
</body>
</html>
