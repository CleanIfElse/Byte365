<?php
	class ChatClient{
		public function SendMessage($chatID, $sender)
		{
			global $db;


		}

		public function GetChatList()
		{
			global $db;

			$q = $db->prepare("SELECT * FROM `chats` WHERE `ChatUser1` = :uid OR `ChatUser2` = :uid ORDER BY `LastChatTime` DESC");
			$q->execute([
				":uid" => $_SESSION['user']['id']
			]);

			$list = '';

			foreach ($q->fetchAll() as $chat)
			{
				if ($chat['ChatUser1'] != $_SESSION['user']['id'])
				{
					$otherUser = $this->UserInfo($chat['ChatUser1']);
				}
				else
				{
					$otherUser = $this->UserInfo($chat['ChatUser2']);
				}

				$list .= '<tr>
					<td class="file first" id="chat-'.$chat['ChatID'].'" style="cursor: pointer;" onclick="openChat('.$chat['ChatID'].')">
						<img src="https://scontent-ort2-1.xx.fbcdn.net/v/t1.0-9/20770383_472127773157326_534623617906662064_n.jpg?oh=10f48031a4a89aa6ce67a9430af58e8e&oe=5A2A8ED2" class="chat-pic">
						<div class="preview-content" style="display: inline;">
							<b class="name">'.htmlspecialchars($otherUser['FirstName']) . ' ' . htmlspecialchars($otherUser['LastName']) . '</b> <br />
							<span class="chat-preview">'.$this->GetChatPreview($chat['ChatID']).'</span>
						</div>
					</td>
					<td class="file last"></td>
				</tr>';
			}

			return $list;
		}

		public static function GetChatPreview($chatID)
		{
			global $db;

			$q = $db->prepare("SELECT * FROM `chatmessages` WHERE `ChatID` = :cid ORDER BY `Time` DESC LIMIT 1");
			$q->execute([
				":cid" => $chatID
			]);

			$chat = $q->fetch();

			if ($chat['ChatRead'] == 'no')
				return '<b>'.htmlspecialchars($chat['Contents']).'</b>';
			else
				return htmlspecialchars($chat['Contents']);
		}

		public static function UserInfo($uid)
		{
			global $db;

			$q = $db->prepare("SELECT * FROM `users` WHERE `UserID` = :id");
			$q->execute([
				":id" => $uid
			]);

			return $q->fetch();
		}
	}
