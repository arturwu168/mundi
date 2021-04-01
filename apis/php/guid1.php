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
	* ѩ���㷨�
	* @package app\helpers
	*/
	class SnowFlake
	{
		const EPOCH_OFFSET = 0;  //ƫ�ƕr�g��,ԓ�r�gһ��ҪС춵�һ��id���ɵĕr�g,�҃�����(Ӱ��㷨����Ч���Õr�g)

		const SIGN_BITS = 1;        //���λ(��̖λ)λ����ʼ�K��0��������
		const TIMESTAMP_BITS = 41;  //�r�g��λ��(�㷨Ĭ�J41λ,����ʹ��69��)
		const DATA_CENTER_BITS = 5;  //IDC(��������)��̖λ��(�㷨Ĭ�J5λ,���֧�ֲ���32�����c)
		const MACHINE_ID_BITS = 5;  //�C����̖λ��(�㷨Ĭ�J5λ,���֧�ֲ���32�����c)
		const SEQUENCE_BITS = 12;   //Ӌ������̖λ��,��һϵ�е�����id������֧��ͬһ���cͬһ�������ɶ���ID��̖(�㷨Ĭ�J12λ,֧��ÿ�����cÿ����a��4096��ID��̖)��

		/**
		 * @var integer �������ľ�̖
		 */
		protected $data_center_id;

		/**
		 * @var integer �C����̖
		 */
		protected $machine_id;

		/**
		 * @var null|integer ��һ������idʹ�õĕr�g��(���뼉�e)
		 */
		protected $lastTimestamp = null;

		/**
		 * @var int
		 */
		protected $sequence = 1;    //����̖
		protected $signLeftShift = self::TIMESTAMP_BITS + self::DATA_CENTER_BITS + self::MACHINE_ID_BITS + self::SEQUENCE_BITS;  //��̖λ��λ��λ��
		protected $timestampLeftShift = self::DATA_CENTER_BITS + self::MACHINE_ID_BITS + self::SEQUENCE_BITS;    //�r�g����λ��λ��
		protected $dataCenterLeftShift = self::MACHINE_ID_BITS + self::SEQUENCE_BITS;   //IDC��λ��λ��
		protected $machineLeftShift = self::SEQUENCE_BITS;  //�C����̖��λ��λ��
		protected $maxSequenceId = -1 ^ (-1 << self::SEQUENCE_BITS);    //�������̖
		protected $maxMachineId = -1 ^ (-1 << self::MACHINE_ID_BITS);   //���C����̖
		protected $maxDataCenterId = -1 ^ (-1 << self::DATA_CENTER_BITS);   //��󔵓����ľ�̖

		/**
		 * @param integer $dataCenter_id �������ĵ�ΨһID(���ʹ�ö�����������,��Ҫ�O�ô�ID���ԅ^��)
		 * @param integer $machine_id �C����ΨһID (���ʹ�ö��_�C��,��Ҫ�O�ô�ID���ԅ^��)
		 * @throws \Exception
		 */
		public function __construct($dataCenter_id = 0, $machine_id = 0)
		{
			if ($dataCenter_id > $this->maxDataCenterId) {
				throw new \Exception('�������ľ�̖ȡֵ������:0-' . $this->maxDataCenterId);
			}
			if ($machine_id > $this->maxMachineId) {
				throw new \Exception('�C����̖��̖ȡֵ������:0-' . $this->maxMachineId);
			}
			$this->data_center_id = $dataCenter_id;
			$this->machine_id = $machine_id;
		}

		/**
		 * ʹ��ѩ���㷨����һ��ΨһID
		 * @return string ���ɵ�ID
		 * @throws \Exception
		 */
		public function generateID()
		{
			//echo $this->lastTimestamp.'<br />';

			$sign = 0; //��̖λ,ֵʼ�K��0
			$timestamp = $this->getUnixTimestamp();
			if ($timestamp < $this->lastTimestamp) {
				throw new \Exception('�r�g������!');
			}

			//�c�ϴΕr�g�����,��Ҫ��������̖.����Ȅt��������̖
			if ($timestamp == $this->lastTimestamp) {
				$sequence = ++$this->sequence;
				if ($sequence == $this->maxSequenceId) { //�������̖���ޣ��t��Ҫ���«@ȡ�r�g
					$timestamp = $this->getUnixTimestamp();
					while ($timestamp <= $this->lastTimestamp) {    //�r�g��ͬ�t����
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
		 * �@ȡȥ��ǰ�r�g��
		 *
		 * @return integer ���뼉�e�ĕr�g��
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