<?php
	class Toxicity{
		public static function Check($string)
		{
			$string = strtolower($string);
			$string = str_replace('.', '', $string);
			$string = str_replace(',', '', $string);
			$string = str_replace('!', '', $string);
			$string = str_replace('?', '', $string);

			if ($string == '')
				return 0;

			$level3 = array(
				'fuck',
				'cunt',
				'whore',
				'slut',
				'nigger',
				'faggot',
				'fag',
				'assbandit',
				'assbanger',
				'ass-pirate',
				'bitchtits',
				'clit',
				'cock',
				'pussy',
				'cumslut',
				'faggit',
				'fudgepacker',
				'jigaboo',
				'kunt',
				'rimjob',
				'niggers',
				'nigers',
				'niggar',
				'fucking',
				'nigga',
				'niggas'
			);

			$level2 = array(
				'cum',
				'cooter',
				'muff',
				'queer',
				'retard',
				'splooge',
				'spick',
				'skullfuck',
				'kys',
				'rape',
				'bitch'
			);

			$level1 = array(
				'bastard',
				'beaner',
				'boner',
				'bullshit',
				'blowjob',
				'damn',
				'dick',
				'douche',
				'dyke',
				'honkey',
				'jizz',
				'penis',
				'vagina',
				'puto',
				'queef',
				'shit',
				'ass',
				'damb',
				'idiot',
				'vajayjay',
				'die',
				'suicide',
				'j1zz',
				'idiots'
			);

			$quotes = array(
				"kill yourself",
				"hang yourself",
				"suck my dick",
				"fucked your mom",
				"kys fgt",
				"suck my cock",
				"rape you",
				"kill myself",
				"hate my life"
			);

			$length = strlen($string);

			$exploded = explode(' ', $string);

			$meter = 0;

			foreach ($exploded as $word)
			{
				if (in_array($word, $level3))
					$meter = $meter + 6;

				if (in_array($word, $level2))
					$meter = $meter + 3;

				if (in_array($word, $level1))
					$meter = $meter + 2;
			}

			foreach ($quotes as $quote)
			{
				if (strpos($string, $quote))
					$meter = $meter + 4;
			}

			return (($meter / $length * 2) / .35) * 100;
		}
	}
?>
