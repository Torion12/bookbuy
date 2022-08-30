<?php

class Notification {
   private $_pusher = null;
   private $_db = null;
   private $_mail = null;

   public function __construct() {
      $this->_db = DB::getInstance();

      $options = array(
         'cluster' => 'ap1',
         'useTLS' => true
      );

      $this->_pusher = new Pusher\Pusher(
         '9793b4a9d2a3567cf558',
         '20739a4726498fcb0585',
         '1195943',
         $options
      );

      //Instantiation and passing `true` enables exceptions
      $this->_mail = new PHPMailer\PHPMailer\PHPMailer(true);

      //Server settings
      $this->_mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
      $this->_mail->isSMTP();
      $this->_mail->Host       = 'smtp.gmail.com';
      $this->_mail->SMTPAuth   = true;
      $this->_mail->Username   = 'julatonedwinv@gmail.com'; 
      $this->_mail->Password   = '$SSkudd1993';
      $this->_mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
      $this->_mail->Port       = 587;

      $this->_mail->isHTML(true);
   }

   public function sendNotification($to, $type, $data) {
      $user = new User();

      $this->_db->insert('notifications', [
         'message' => $data['message'] ?? '',
         'type' => $to . '-' . $type,
         'user_id' => $user->isLoggedIn() ? $user->data()->id : 0
      ]);
      $this->_pusher->trigger($to, $type, $data);
   }

   public function sendEmail($email, $name, $content) {
      //Recipients
      $this->_mail->setFrom('from@example.com', 'BookBuy');
      $this->_mail->Subject = 'BookBuy - Notification';
      $this->_mail->Body    = $content;
      $this->_mail->addAddress($email, $name);
      $this->_mail->send();
   }
}