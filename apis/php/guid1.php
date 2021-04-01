<?php
	require_once('lib.php');
    require_once('dbs.php');

	require_once ('thirds/snowflake/vendor/autoload.php');


	//sleep(1);

	set_time_limit(0);

	$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
	echo $now->format('Y-m-d H:i:s.u')."<br />--------------------------<br /><br />";

	$sTime1=microtime(true);

	$pRJs1=new RetJson();
	$pDbs1=$gDb['isd_isdmain'];

	$pSql4 = 'insert into sysdbf0003(genVar0002) values ';

	for ($i=0; $i<500000; $i++){

		if ($i===0){
			$pSql4.='(UUID_SHORT())';
		}else{
			$pSql4.=', (UUID_SHORT())';
		}

	}
	$pDbs1->exec($pSql4);

	//echo $pSql4.'<br>';

	//$pSth4 = $pDbs1->prepare($pSql4);
	//$pSth4->execute([]);

	$pDbs1=null;

	$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
	echo "<br />--------------------------<br />".$now->format('Y-m-d H:i:s.u');

	$eTime1=microtime(true);

	echo '<br />'.($eTime1-$sTime1);

	set_time_limit(30);

	exit;

	//echo $_SESSION['s_serial'].'<br>';

	/*for ($i=0; $i<10; $i++){
		echo fGuid();
		echo '<br />';
	}*/

	/**
	* 雪花算法類
	* @package app\helpers
	*/
	class SnowFlake
	{
		const EPOCH_OFFSET = 0;  //偏移時間戳,該時間一定要小於第一個id生成的時間,且儘量大(影響算法的有效可用時間)

		const SIGN_BITS = 1;        //最高位(符號位)位數，始終爲0，不可用
		const TIMESTAMP_BITS = 41;  //時間戳位數(算法默認41位,可以使用69年)
		const DATA_CENTER_BITS = 5;  //IDC(數據中心)編號位數(算法默認5位,最多支持部署32個節點)
		const MACHINE_ID_BITS = 5;  //機器編號位數(算法默認5位,最多支持部署32個節點)
		const SEQUENCE_BITS = 12;   //計數序列號位數,即一系列的自增id，可以支持同一節點同一毫秒生成多個ID序號(算法默認12位,支持每個節點每毫秒產生4096個ID序號)。

		/**
		 * @var integer 數據中心編號
		 */
		protected $data_center_id;

		/**
		 * @var integer 機器編號
		 */
		protected $machine_id;

		/**
		 * @var null|integer 上一次生成id使用的時間戳(毫秒級別)
		 */
		protected $lastTimestamp = null;

		/**
		 * @var int
		 */
		protected $sequence = 1;    //序列號
		protected $signLeftShift = self::TIMESTAMP_BITS + self::DATA_CENTER_BITS + self::MACHINE_ID_BITS + self::SEQUENCE_BITS;  //符號位左位移位數
		protected $timestampLeftShift = self::DATA_CENTER_BITS + self::MACHINE_ID_BITS + self::SEQUENCE_BITS;    //時間戳左位移位數
		protected $dataCenterLeftShift = self::MACHINE_ID_BITS + self::SEQUENCE_BITS;   //IDC左位移位數
		protected $machineLeftShift = self::SEQUENCE_BITS;  //機器編號左位移位數
		protected $maxSequenceId = -1 ^ (-1 << self::SEQUENCE_BITS);    //最大序列號
		protected $maxMachineId = -1 ^ (-1 << self::MACHINE_ID_BITS);   //最大機器編號
		protected $maxDataCenterId = -1 ^ (-1 << self::DATA_CENTER_BITS);   //最大數據中心編號

		/**
		 * @param integer $dataCenter_id 數據中心的唯一ID(如果使用多個數據中心,需要設置此ID用以區分)
		 * @param integer $machine_id 機器的唯一ID (如果使用多臺機器,需要設置此ID用以區分)
		 * @throws \Exception
		 */
		public function __construct($dataCenter_id = 0, $machine_id = 0)
		{
			if ($dataCenter_id > $this->maxDataCenterId) {
				throw new \Exception('數據中心編號取值範圍爲:0-' . $this->maxDataCenterId);
			}
			if ($machine_id > $this->maxMachineId) {
				throw new \Exception('機器編號編號取值範圍爲:0-' . $this->maxMachineId);
			}
			$this->data_center_id = $dataCenter_id;
			$this->machine_id = $machine_id;
		}

		/**
		 * 使用雪花算法生成一個唯一ID
		 * @return string 生成的ID
		 * @throws \Exception
		 */
		public function generateID()
		{
			//echo $this->lastTimestamp.'<br />';

			$sign = 0; //符號位,值始終爲0
			$timestamp = $this->getUnixTimestamp();
			if ($timestamp < $this->lastTimestamp) {
				throw new \Exception('時間倒退了!');
			}

			//與上次時間戳相等,需要生成序列號.不相等則重置序列號
			if ($timestamp == $this->lastTimestamp) {
				$sequence = ++$this->sequence;
				if ($sequence == $this->maxSequenceId) { //如果序列號超限，則需要重新獲取時間
					$timestamp = $this->getUnixTimestamp();
					while ($timestamp <= $this->lastTimestamp) {    //時間相同則阻塞
						$timestamp = $this->getUnixTimestamp();
					}
					$this->sequence = 0;
					$sequence = ++$this->sequence;
				}
			} else {
				$this->sequence = 0;
				$sequence = ++$this->sequence;
			}

			$this->lastTimestamp = $timestamp;
			$time = (int)($timestamp - self::EPOCH_OFFSET);
			$id = ($sign << $this->signLeftShift) | ($time << $this->timestampLeftShift) | ($this->data_center_id << $this->dataCenterLeftShift) | ($this->machine_id << $this->machineLeftShift) | $sequence;

			return (string)$id;
		}

		/**
		 * 獲取去當前時間戳
		 *
		 * @return integer 毫秒級別的時間戳
		 */
		private function getUnixTimestamp()
		{
			return floor(microtime(true) * 1000);
		}
	}


	//echo floor(microtime(true) * 1000)."<br>";

	$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
	echo $now->format('Y-m-d H:i:s.u')."<br />--------------------------<br />";

	


	//$pSf1=new SnowFlake;

	//$IdWorker1 = \wantp\Snowflake\IdWorker::getIns();

	//$pSf1->__construct(1,1);
	//echo 99999999999;

	/*for ($i=0; $i<100; $i++){
		$pUid1=$pSf1->generateID();
		//$pUid1=$IdWorker1->id();

		//echo $pUid1.", ";
	}*/


	//$pBguid=fGuid();
	//echo fGuid();
	//echo '<br />';
	//echo microtime(true).'<br />';

	//$pDate1='3020-12-30 23:59:59';

	//echo $pDate1." ".strtotime("2520-02-23").'<br />';
	//echo $pDate1." ".strtotime($pDate1).'<br />';
	//echo '2220-12-31 23:59:59'." ".strtotime('2220-12-31 23:59:59').'<br />';

	//$pRetv1='';
	//$pBguid1=fGuid();
	//echo $pBguid1.'<br />';
	//$pBguid2=$pBguid1;

	/*for ($i=0; $i<(100-1); $i++){
		//usleep(1000000);

		//echo uniqid(uniqid()).'<br />';

		//fGuid();
		//if ()

		$pBguid2=fGuid($pBguid2);

		echo $pBguid2;

		if ($pBguid2===-1){
			echo '<br />';
			break;
		}

		//echo date('Ymdhis') . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);

		echo '<br />';
	
	}*/
	
	//echo $pRetv1.'<br />';

	$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
	echo "<br />--------------------------<br />".$now->format('Y-m-d H:i:s.u');

?>