## アプリケーション名
「Atte（アッテ）」<br>
Atteは勤怠管理ができるアプリです。 会員登録することで毎日の勤怠を記録でき、日付別の勤怠記録の閲覧もできます。また個々人毎の出退勤も確認できます。


## 作成した目的
このアプリはLaravel学習の総まとめとして作成しました。 与えられた要件や成果物イメージをもとに、テーブル定義・ER図作成・コーディングをおこないました。

## アプリケーション URL
開発環境：http://localhost/<br>
phpMyAdmin：http://localhost:8080/<br>
mailhog:http://localhost:8025/<br>

## 他のリポジトリ
なし

## 機能一覧
* 会員登録機能（入力項目は名前、メールアドレス、パスワード、確認用パスワード）
* メール認証機能（会員登録時、メールが届き、認証することで会員登録ができる）
* ログイン（メールアドレスとパスワードで認証）
* ログアウト機能
* 勤怠の打刻機能<br>
　→出勤・退勤の打刻（1日に1回のみ、退勤するとその日は出勤ボタンが押せなくなる）<br>
　→休憩開始・終了の打刻（1日に何度でも可能）
* 全ユーザーの日付別勤怠記録の表示
* ユーザー一覧の表示<br>
　→そのユーザー個別の日付別勤怠記録の表示


## 使用技術
* PHP 7.4.9
* Laravel 8.83.27
* MySQL　8.0.26

## テーブル設計
<img width="823" alt="スクリーンショット 2024-05-15 18 40 14" src="https://github.com/Binse-Park/Parser-android-s-dumpstate/assets/149504438/2ecc6670-f204-4d8c-a0d2-82cb34a2b594">
<img width="776" alt="スクリーンショット 2024-05-15 18 40 45" src="https://github.com/Binse-Park/Parser-android-s-dumpstate/assets/149504438/5dbddda0-885e-4251-bd4b-b8426f1fdcf4">


## ER図
<img width="763" alt="スクリーンショット 2024-05-16 21 15 49" src="https://github.com/mizuki44/ability-test/assets/149504438/cfc5e1cf-af43-4f10-ae6c-2a710932fd78">



## 環境構築
Docker ビルド
1. git clone git@github.com:mizuki44/attendance_system.git
2. DockerDesktopアプリを立ち上げる
3. docker-compose up -d --build

## Laravel環境構築<br>
1. コンテナに入る<br>
docker-compose exec php bash
2. composerをインストールする
composer install
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更する。
または、新しく.envファイルを作成
4. .envに以下の環境変数を追加<br>
DB_CONNECTION=mysql<br>
DB_HOST=mysql<br>
DB_PORT=3306<br>
DB_DATABASE=laravel_db<br>
DB_USERNAME=laravel_user<br>
DB_PASSWORD=laravel_pass<br>
<br>
MAIL_FROM_ADDRESS=info@example.com<br>

5. keyを生成する<br>
php artisan key:generate

6. マイグレーションの実行<br>
php artisan migrate
