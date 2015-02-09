<?php

/**
 * This is the model class for table "td_members".
 *
 * The followings are the available columns in table 'td_members':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $pass_salt
 * @property string $login_key
 * @property integer $mgroup
 * @property string $msub_group
 * @property string $title
 * @property integer $joined
 * @property string $ipadd
 * @property integer $open_tickets
 * @property integer $tickets
 * @property double $rating
 * @property integer $rating_total
 * @property integer $votes
 * @property integer $email_notify
 * @property integer $email_html
 * @property integer $email_new_ticket
 * @property integer $email_ticket_reply
 * @property integer $email_announce
 * @property integer $ban_ticket_center
 * @property integer $ban_ticket_open
 * @property integer $ban_ticket_escalate
 * @property integer $ban_ticket_rate
 * @property integer $ban_kb
 * @property integer $ban_kb_comment
 * @property integer $ban_kb_rate
 * @property string $time_zone
 * @property integer $dst_active
 * @property string $lang
 * @property integer $skin
 * @property integer $email_val
 * @property integer $admin_val
 * @property integer $email_staff_new_ticket
 * @property integer $email_staff_ticket_reply
 * @property integer $use_rte
 * @property string $cpfields
 * @property string $rss_key
 * @property integer $assigned
 * @property string $signature
 * @property integer $auto_sig
 */
class TdMembers extends FormModel
{
    public $name;
    public $email;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'td_members';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('cpfields, signature', 'required'),
			array('mgroup, joined, open_tickets, tickets, rating_total, votes, email_notify, email_html, email_new_ticket, email_ticket_reply, email_announce, ban_ticket_center, ban_ticket_open, ban_ticket_escalate, ban_ticket_rate, ban_kb, ban_kb_comment, ban_kb_rate, dst_active, skin, email_val, admin_val, email_staff_new_ticket, email_staff_ticket_reply, use_rte, assigned, auto_sig', 'numerical', 'integerOnly'=>true),
			array('rating', 'numerical'),
			array('name, email, password, pass_salt, login_key, msub_group, title, rss_key', 'length', 'max'=>255),
			array('ipadd', 'length', 'max'=>32),
			array('time_zone, lang', 'length', 'max'=>3),
			array('id, name, email, password, pass_salt, login_key, mgroup, msub_group, title, joined, ipadd, open_tickets, tickets, rating, rating_total, votes, email_notify, email_html, email_new_ticket, email_ticket_reply, email_announce, ban_ticket_center, ban_ticket_open, ban_ticket_escalate, ban_ticket_rate, ban_kb, ban_kb_comment, ban_kb_rate, time_zone, dst_active, lang, skin, email_val, admin_val, email_staff_new_ticket, email_staff_ticket_reply, use_rte, cpfields, rss_key, assigned, signature, auto_sig', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'email' => 'Email',
			'password' => 'Password',
			'pass_salt' => 'Pass Salt',
			'login_key' => 'Login Key',
			'mgroup' => 'Mgroup',
			'msub_group' => 'Msub Group',
			'title' => 'Title',
			'joined' => 'Joined',
			'ipadd' => 'Ipadd',
			'open_tickets' => 'Open Tickets',
			'tickets' => 'Tickets',
			'rating' => 'Rating',
			'rating_total' => 'Rating Total',
			'votes' => 'Votes',
			'email_notify' => 'Email Notify',
			'email_html' => 'Email Html',
			'email_new_ticket' => 'Email New Ticket',
			'email_ticket_reply' => 'Email Ticket Reply',
			'email_announce' => 'Email Announce',
			'ban_ticket_center' => 'Ban Ticket Center',
			'ban_ticket_open' => 'Ban Ticket Open',
			'ban_ticket_escalate' => 'Ban Ticket Escalate',
			'ban_ticket_rate' => 'Ban Ticket Rate',
			'ban_kb' => 'Ban Kb',
			'ban_kb_comment' => 'Ban Kb Comment',
			'ban_kb_rate' => 'Ban Kb Rate',
			'time_zone' => 'Time Zone',
			'dst_active' => 'Dst Active',
			'lang' => 'Lang',
			'skin' => 'Skin',
			'email_val' => 'Email Val',
			'admin_val' => 'Admin Val',
			'email_staff_new_ticket' => 'Email Staff New Ticket',
			'email_staff_ticket_reply' => 'Email Staff Ticket Reply',
			'use_rte' => 'Use Rte',
			'cpfields' => 'Cpfields',
			'rss_key' => 'Rss Key',
			'assigned' => 'Assigned',
			'signature' => 'Signature',
			'auto_sig' => 'Auto Sig',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('pass_salt',$this->pass_salt,true);
		$criteria->compare('login_key',$this->login_key,true);
		$criteria->compare('mgroup',$this->mgroup);
		$criteria->compare('msub_group',$this->msub_group,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('joined',$this->joined);
		$criteria->compare('ipadd',$this->ipadd,true);
		$criteria->compare('open_tickets',$this->open_tickets);
		$criteria->compare('tickets',$this->tickets);
		$criteria->compare('rating',$this->rating);
		$criteria->compare('rating_total',$this->rating_total);
		$criteria->compare('votes',$this->votes);
		$criteria->compare('email_notify',$this->email_notify);
		$criteria->compare('email_html',$this->email_html);
		$criteria->compare('email_new_ticket',$this->email_new_ticket);
		$criteria->compare('email_ticket_reply',$this->email_ticket_reply);
		$criteria->compare('email_announce',$this->email_announce);
		$criteria->compare('ban_ticket_center',$this->ban_ticket_center);
		$criteria->compare('ban_ticket_open',$this->ban_ticket_open);
		$criteria->compare('ban_ticket_escalate',$this->ban_ticket_escalate);
		$criteria->compare('ban_ticket_rate',$this->ban_ticket_rate);
		$criteria->compare('ban_kb',$this->ban_kb);
		$criteria->compare('ban_kb_comment',$this->ban_kb_comment);
		$criteria->compare('ban_kb_rate',$this->ban_kb_rate);
		$criteria->compare('time_zone',$this->time_zone,true);
		$criteria->compare('dst_active',$this->dst_active);
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('skin',$this->skin);
		$criteria->compare('email_val',$this->email_val);
		$criteria->compare('admin_val',$this->admin_val);
		$criteria->compare('email_staff_new_ticket',$this->email_staff_new_ticket);
		$criteria->compare('email_staff_ticket_reply',$this->email_staff_ticket_reply);
		$criteria->compare('use_rte',$this->use_rte);
		$criteria->compare('cpfields',$this->cpfields,true);
		$criteria->compare('rss_key',$this->rss_key,true);
		$criteria->compare('assigned',$this->assigned);
		$criteria->compare('signature',$this->signature,true);
		$criteria->compare('auto_sig',$this->auto_sig);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
