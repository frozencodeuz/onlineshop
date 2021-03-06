<?php

namespace app\models;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "contacts".
 *
 * @property int $id
 * @property string $title_ru
 * @property string $title_en
 * @property string $content_ru
 * @property string $content_en
 * @property string $address
 * @property string $main_email
 * @property string $emial
 * @property string $lat
 * @property string $lng
 */
class Contacts extends \yii\db\ActiveRecord
{

    public $phones;
    public $phone;
    public $lang_uz;
    public $lang_ru;
    public $lang_en;
    public $langs;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_ru', 'title_en'], 'required'],
            [['content_ru', 'content_en'], 'string'],
            [['title_ru', 'title_en', 'address', 'main_email', 'emial', 'lat', 'lng'], 'string', 'max' => 255],
            [['phones','phone','langs','lang_ru','lang_uz','lang_en'],'safe'],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (is_array($this->phones) ) {
            if (!$this->isNewRecord) {
                Phones::deleteAll();
            }
            foreach ( $this->phones as $item ) {
                try {
                    Yii::$app->db->createCommand()->insert('phones', [
                        'phone' => $item['phone'],
                        'lang_ru' => $item['lang_ru'],
                        'lang_uz' => $item['lang_uz'],
                        'lang_en' => $item['lang_en'],

                    ])->execute();
                } catch ( Exception $e ) {
                    echo $e->getMessage();
                }
            }
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function afterFind()
    {
        $phones = Phones::find()->all();
        foreach ($phones as $k => $item):
            $this->phones[$k]['phone'] = $item->phone;
            $this->phones[$k]['lang_ru'] = $item->lang_ru;
            $this->phones[$k]['lang_uz'] = $item->lang_uz;
            $this->phones[$k]['lang_en'] = $item->lang_en;
        endforeach;

        parent::afterFind(); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title_ru' => Yii::t('app', 'Title Ru'),
            'title_en' => Yii::t('app', 'Title En'),
            'content_ru' => Yii::t('app', 'Content Ru'),
            'content_en' => Yii::t('app', 'Content En'),
            'address' => Yii::t('app', 'Address'),
            'main_email' => Yii::t('app', 'Main Email'),
            'emial' => Yii::t('app', 'Emial'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'phones' => Yii::t('app', 'Phones'),
            'phone' => Yii::t('app', 'Phone'),
        ];
    }
    public function getTitle(){

        if (Yii::$app->language == 'ru'):  return $this->title_ru;

        endif;
        if (Yii::$app->language == 'en'):  return $this->title_en;

        endif;
    }

    public function getContent(){

        if (Yii::$app->language == 'ru'):  return $this->content_ru;

        endif;
        if (Yii::$app->language == 'en'):  return $this->content_en;

        endif;
    }
}
