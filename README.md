<h1>◆livehouse◆</h1>
livewireを使用し、ライブハウスを想定した予約システムを構築しました。<br><br>

<a href = "http://18.182.16.40/livehouse/public/">サイトはこちら</a><br>

仕様ライブラリ<br>
php:8.02<br>
Laravel:9.19<br>
livewire:2.11<br>
jetstream:3.00<br>
Laravel/Vite<br>
AWS<br>

<h2>◆管理者権限◆</h2><br>

admin@admin.com<br>
password123<br><br>

マネージャー権限以上で、ナビゲーションにイベント管理の項目が表示されるように設定されてあります。

<img src = "https://github.com/pocari1210/livehouse/assets/98627989/6b582da4-4ff3-48d6-b045-fef93f2c588d"><br>

イベント管理では、過去のイベントの確認、新規のイベントの登録を行うことができます。<br>
<img src = "https://github.com/pocari1210/livehouse/assets/98627989/27e6fd6c-f6fb-4514-8ff5-f5bc75b949ca" width = 300px height=200px>
<img src = "https://github.com/pocari1210/livehouse/assets/98627989/51ad14fa-ba7a-4f80-83cf-9776025f4965" width = 300px height=200px><br><br>

<h2>◆ユーザー権限◆</h2><br>
user@user.com<br>
password123<br><br>

マイページより、予約したイベント、過去に予約したイベントを確認することができます。<br><br>
<img src = "https://github.com/pocari1210/livehouse/assets/98627989/88dfd91a-6280-4c7c-892d-9e8568443eb6" width = 300px height=200px><br><br>


<h2>作成した背景</h2>
病院の予約や美容室、試合の遠征の際、バスの予約をネットですることがあり、<br>
私自身も構築してみたいと思い、学習を行いました。

<h2>身についたこと・理解できたこと</h2>
・カレンダーのレイアウト方法を学習することができた<br><br>

・flat pickerを用いて、日付のライブラリについて理解ができた<br>

・livewireをしようし、datepickerの日付の変更方法や、イベントの重複の判定、<br>
  満席時の判定方法を学習することができました。

<h2>今後の課題点(できるようになりたいこと)</h2>
・AWSでデプロイをしてから、flat pickerが動かなかったので、原因を特定し、<br>
動かせるようにしたい<br><br>

・イベントが満席になったら色を赤くしたり、満席などの文字を追記できるようになりたい<br>
※調べた結果裏でjavascriptがうごいていたため、Laravelのeloquantで操作がうまくいかなかったので、<br>
実装方法を再度検討し、修正を行いたい<br><br>

・中間テーブルやjoinSubのデータベース周りの知識を身に着けていきたい<br>
