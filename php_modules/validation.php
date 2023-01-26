<?php

	// 入力チェック用(バリデーション)関数
	function validation($data) {

		$error = array();

		// 氏名のバリデーション
		// 未入力チェック
		if( empty($data['your_name']) ) {
			$error[] = "「お名前」は必ず入力してください。";

			// 文字の長さチェック
		} elseif( 20 < mb_strlen($data['your_name']) ) {
			$error[] = "「お名前」は20文字以内で入力してください。";
		}

		// メールアドレスのバリデーション
		if( empty($data['email']) ) {
			$error[] = "「メールアドレス」は必ず入力してください。";

			// 形式チェック
		} elseif( !preg_match( '/^[0-9a-z_.\/?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$/', $data['email']) ) {
			$error[] = "「メールアドレス」は正しい形式で入力してください。";
		}

		// お問い合わせ種類のバリデーション
		if( empty($data['category']) ) {
			$error[] = "「お問い合わせの種類」は必ず入力してください。";

			// 形式チェック
		} elseif( (int)$data['category'] < 1 || 3 < (int)$data['category'] ) {
			$error[] = "「お問い合わせの種類」は必ず入力してください。";
		}

		// お問い合わせ内容のバリデーション
		if( empty($data['contact']) ) {
			$error[] = "「お問い合わせ内容」は必ず入力してください。";
		}

		return $error;
	}
