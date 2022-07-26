<?php

foreach ( $field['fields'] as $field ) {
	if ( isset( $field['type'] ) ) {
		echo wpmm_settings()->wpmm_field_type( $field );
	}
}

