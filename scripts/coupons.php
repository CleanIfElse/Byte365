<?php
class Coupons
{
	public static function GetCoupons()
	{
		global $db;

		$s = $db->prepare("SELECT * FROM `coupons`");
		$s->execute();
		$s = $s->fetchAll();

		$return = '';

	     foreach ($s as $coupon)
	     {
		     $return .= '
		     			<table style="border-top: 1px solid #d4d4d4;">
						<tr>
			     				<td>
								<span style="color: #ff5050; font-weight: 300;">CODE:</span> '.htmlspecialchars($coupon['Name']).' <span style="background: red; font-weight: 300; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; color: #fff; font-size: 12px;">'.$coupon['PercentOff'].'%</span>
							</td>
							<td style="float: right; padding-right: 20px;">
								<a href="?deleteCoupon='.$coupon['ID'].'">Delete</a>
							</td>
						</tr>
					</table>';
	     }

	     return $return;
	}

	public static function DeleteCoupon($coupon)
	{
		global $db;

		$q = $db->prepare("DELETE FROM `coupons` WHERE `ID` = :coupon LIMIT 1");
		$q->execute([":coupon" => $coupon ]);
	}

	public static function SaveCoupon($name, $amount)
	{
		global $db;

		$q = $db->prepare("INSERT INTO `coupons` (`Name`, `PercentOff`, `Uses`) VALUES (:name, :percent, 0)");
		$q->execute([
			":name" => $name,
			":percent" => $amount
		]);
	}
}
