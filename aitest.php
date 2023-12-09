<?php

// アクセストークンの取得
$accessToken = 'f06bd9cdbacee747ad8191bd1a8ce08ce38f7590'; // サービスアカウントのアクセストークン

// Dialogflow APIのエンドポイント設定
$projectId = 'utility-root-405709';
$sessionId = uniqid();
$url = 'https://dialogflow.googleapis.com/v2/projects/' . $projectId . '/agent/sessions/' . $sessionId . ':detectIntent';

// ユーザーのメッセージ
$queryText = 'こんにちは';

// リクエストボディの作成
$body = json_encode([
    'queryInput' => [
        'text' => [
            'text' => $queryText,
            'languageCode' => 'ja'
        ]
    ]
]);

// cURLを使用してリクエストを送信
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
curl_close($ch);

// 応答をデコード
$responseData = json_decode($response, true);

// 応答の処理
// 例: Dialogflowからの応答テキストを表示
if (isset($responseData['queryResult']['fulfillmentText'])) {
    echo $responseData['queryResult']['fulfillmentText'];
}
