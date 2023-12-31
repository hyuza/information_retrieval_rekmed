<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RekamMedis;

/**
 * RekamMedisSearch represents the model behind the search form about `app\models\RekamMedis`.
 */
class RekamMedisSearch extends RekamMedis
{
    public $tanggal_periksa;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rm_id', 'user_id', 'kunjungan_id', 'nadi', 'berat_badan', 'tinggi_badan'], 'integer'],
            [['mr', 'tekanan_darah', 'keluhan_utama', 'anamnesis', 'pemeriksaan_fisik', 'hasil_penunjang', 'deskripsi_tindakan', 'saran_pemeriksaan', 'created', 'modified','pasien_nama','tanggal_periksa'], 'safe'],
            [['respirasi_rate', 'suhu', 'bmi'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = RekamMedis::find();
        //$query->where(['rekam_medis.user_id' => Yii::$app->user->identity->id]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('mr0');
        $query->joinWith('kunjungan');

        // grid filtering conditions
        $query->andFilterWhere([
            'nadi' => $this->nadi,
            'respirasi_rate' => $this->respirasi_rate,
            'suhu' => $this->suhu,
            'berat_badan' => $this->berat_badan,
            'tinggi_badan' => $this->tinggi_badan,
            'bmi' => $this->bmi,
            'modified' => $this->modified,
        ]);
        $query->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'kunjungan.tanggal_periksa', $this->tanggal_periksa])
            ->andFilterWhere(['like', 'rekam_medis.mr', $this->mr])
            ->andFilterWhere(['like', 'tekanan_darah', $this->tekanan_darah])
            ->andFilterWhere(['like', 'keluhan_utama', $this->keluhan_utama])
            ->andFilterWhere(['like', 'anamnesis', $this->anamnesis])
            ->andFilterWhere(['like', 'pemeriksaan_fisik', $this->pemeriksaan_fisik])
            ->andFilterWhere(['like', 'hasil_penunjang', $this->hasil_penunjang])
            ->andFilterWhere(['like', 'deskripsi_tindakan', $this->deskripsi_tindakan])
            ->andFilterWhere(['like', 'saran_pemeriksaan', $this->saran_pemeriksaan])
            ->andFilterWhere(['like', 'rekam_medis.created', $this->created]);

        return $dataProvider;
    }

    public function searchtfidf($params, $allowedDocumentIds = [],$allowedDocumentIds2 = [])
    {
        // print_r($allowedDocumentIds2);die();
        $query = RekamMedis::find();
        //$query->where(['rekam_medis.user_id' => Yii::$app->user->identity->id]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('mr0');
        $query->joinWith('kunjungan');

        // grid filtering conditions
        $query->andFilterWhere([
            'nadi' => $this->nadi,
            'respirasi_rate' => $this->respirasi_rate,
            'suhu' => $this->suhu,
            'berat_badan' => $this->berat_badan,
            'tinggi_badan' => $this->tinggi_badan,
            'bmi' => $this->bmi,
            'modified' => $this->modified,
        ]);
        if (!empty($allowedDocumentIds2)) {
            $query->andWhere(['in', 'rm_id', $allowedDocumentIds2]);
        }
        $query->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'kunjungan.tanggal_periksa', $this->tanggal_periksa])
            ->andFilterWhere(['like', 'rekam_medis.mr', $this->mr])
            ->andFilterWhere(['like', 'tekanan_darah', $this->tekanan_darah])
            ->andFilterWhere(['like', 'keluhan_utama', $this->keluhan_utama])
            ->andFilterWhere(['like', 'anamnesis', $this->anamnesis])
            ->andFilterWhere(['like', 'pemeriksaan_fisik', $this->pemeriksaan_fisik])
            ->andFilterWhere(['like', 'hasil_penunjang', $this->hasil_penunjang])
            ->andFilterWhere(['like', 'deskripsi_tindakan', $this->deskripsi_tindakan])
            ->andFilterWhere(['like', 'saran_pemeriksaan', $this->saran_pemeriksaan])
            ->andFilterWhere(['like', 'rekam_medis.created', $this->created]);

        return $dataProvider;
    }
    
   
   //-------- tambahan lukman 11-3-2018 ------------------> 
    public function searchhpl($params)
    {
        $query = RekamMedis::find();
        $query->where(['rekam_medis.user_id' => Yii::$app->user->identity->id]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('mr0');
        $query->joinWith('kunjungan');

        // grid filtering conditions
        $query->andFilterWhere([
            'nadi' => $this->nadi,
            'respirasi_rate' => $this->respirasi_rate,
            'suhu' => $this->suhu,
            'berat_badan' => $this->berat_badan,
            'tinggi_badan' => $this->tinggi_badan,
            'bmi' => $this->bmi,
            'modified' => $this->modified,
        ]);
        $query->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'kunjungan.tanggal_periksa', $this->tanggal_periksa])
            ->andFilterWhere(['like', 'rekam_medis.mr', $this->mr])
            ->andFilterWhere(['like', 'tekanan_darah', $this->tekanan_darah])
            ->andFilterWhere(['like', 'keluhan_utama', $this->keluhan_utama])
            ->andFilterWhere(['like', 'anamnesis', $this->anamnesis])
            ->andFilterWhere(['like', 'pemeriksaan_fisik', $this->pemeriksaan_fisik])
            ->andFilterWhere(['like', 'hasil_penunjang', $this->hasil_penunjang])
            ->andFilterWhere(['like', 'deskripsi_tindakan', $this->deskripsi_tindakan])
            ->andFilterWhere(['like', 'saran_pemeriksaan', $this->saran_pemeriksaan])
            ->andFilterWhere(['like', 'rekam_medis.created', $this->created])
            ->andFilterWhere(['like', 'MONTH(adddate(spog_hpth,280))', date('n')])
            ->andFilterWhere(['like', 'YEAR(adddate(spog_hpth,280))', date('Y')]);

        return $dataProvider;
    }

    
    public function searchhplminggu($params)
    {
        $query = RekamMedis::find();
        $query->where(['rekam_medis.user_id' => Yii::$app->user->identity->id]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('mr0');
        $query->joinWith('kunjungan');

        // grid filtering conditions
        $query->andFilterWhere([
            'nadi' => $this->nadi,
            'respirasi_rate' => $this->respirasi_rate,
            'suhu' => $this->suhu,
            'berat_badan' => $this->berat_badan,
            'tinggi_badan' => $this->tinggi_badan,
            'bmi' => $this->bmi,
            'modified' => $this->modified,
        ]);
        $query->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'kunjungan.tanggal_periksa', $this->tanggal_periksa])
            ->andFilterWhere(['like', 'rekam_medis.mr', $this->mr])
            ->andFilterWhere(['like', 'tekanan_darah', $this->tekanan_darah])
            ->andFilterWhere(['like', 'keluhan_utama', $this->keluhan_utama])
            ->andFilterWhere(['like', 'anamnesis', $this->anamnesis])
            ->andFilterWhere(['like', 'pemeriksaan_fisik', $this->pemeriksaan_fisik])
            ->andFilterWhere(['like', 'hasil_penunjang', $this->hasil_penunjang])
            ->andFilterWhere(['like', 'deskripsi_tindakan', $this->deskripsi_tindakan])
            ->andFilterWhere(['like', 'saran_pemeriksaan', $this->saran_pemeriksaan])
            ->andFilterWhere(['like', 'rekam_medis.created', $this->created])
            ->andFilterWhere(['like', 'WEEK(adddate(spog_hpth,280))', date('W') - 1])
            ->andFilterWhere(['like', 'YEAR(adddate(spog_hpth,280))', date('Y')]);

        return $dataProvider;
    }
    
    //-------- end of tambahan 11-3-2018 ------------------>
    
}
