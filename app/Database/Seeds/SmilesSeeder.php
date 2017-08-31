<?php

use Phinx\Seed\AbstractSeed;

class SmilesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $this->execute('TRUNCATE smiles');

        $table = $this->table('smiles');

        $data = [
            ['cats' => 1, 'name' => ').gif', 'code' => ':)'],
            ['cats' => 1, 'name' => '(.gif', 'code' => ':('],
            ['cats' => 1, 'name' => '4moks.gif', 'code' => ':4moks'],
            ['cats' => 1, 'name' => 'D.gif', 'code' => ':D'],
            ['cats' => 1, 'name' => 'E.gif', 'code' => ':E'],
            ['cats' => 1, 'name' => 'aaa.gif', 'code' => ':aaa'],
            ['cats' => 1, 'name' => 'agree.gif', 'code' => ':agree'],
            ['cats' => 1, 'name' => 'airkiss.gif', 'code' => ':airkiss'],
            ['cats' => 1, 'name' => 'atlet.gif', 'code' => ':atlet'],
            ['cats' => 1, 'name' => 'baby.gif', 'code' => ':baby'],
            ['cats' => 1, 'name' => 'bant.gif', 'code' => ':bant'],
            ['cats' => 1, 'name' => 'be.gif', 'code' => ':be'],
            ['cats' => 1, 'name' => 'blin.gif', 'code' => ':blin'],
            ['cats' => 1, 'name' => 'blum.gif', 'code' => ':blum'],
            ['cats' => 1, 'name' => 'bomba.gif', 'code' => ':bomba'],
            ['cats' => 1, 'name' => 'bounce.gif', 'code' => ':bounce'],
            ['cats' => 1, 'name' => 'bugaga.gif', 'code' => ':bugaga'],
            ['cats' => 1, 'name' => 'buhoj.gif', 'code' => ':buhoj'],
            ['cats' => 1, 'name' => 'bwink.gif', 'code' => ':bwink'],
            ['cats' => 1, 'name' => 'cold.gif', 'code' => ':cold'],
            ['cats' => 1, 'name' => 'cool.gif', 'code' => ':cool'],
            ['cats' => 1, 'name' => 'cry.gif', 'code' => ':cry'],
            ['cats' => 1, 'name' => 'ded.gif', 'code' => ':ded'],
            ['cats' => 1, 'name' => 'derisive.gif', 'code' => ':derisive'],
            ['cats' => 1, 'name' => 'drool.gif', 'code' => ':drool'],
            ['cats' => 1, 'name' => 'duma.gif', 'code' => ':duma'],
            ['cats' => 1, 'name' => 'exercise.gif', 'code' => ':exercise'],
            ['cats' => 1, 'name' => 'faq.gif', 'code' => ':faq'],
            ['cats' => 1, 'name' => 'fermer.gif', 'code' => ':fermer'],
            ['cats' => 1, 'name' => 'fingal.gif', 'code' => ':fingal'],
            ['cats' => 1, 'name' => 'flirt.gif', 'code' => ':flirt'],
            ['cats' => 1, 'name' => 'fuck.gif', 'code' => ':fuck'],
            ['cats' => 1, 'name' => 'girl_blum.gif', 'code' => ':girl_blum'],
            ['cats' => 1, 'name' => 'girl_bye.gif', 'code' => ':girl_bye'],
            ['cats' => 1, 'name' => 'girl_cry.gif', 'code' => ':girl_cry'],
            ['cats' => 1, 'name' => 'girl_hide.gif', 'code' => ':girl_hide'],
            ['cats' => 1, 'name' => 'girl_wink.gif', 'code' => ':girl_wink'],
            ['cats' => 1, 'name' => 'girls.gif', 'code' => ':girls'],
            ['cats' => 1, 'name' => 'happy.gif', 'code' => ':happy'],
            ['cats' => 1, 'name' => 'heart.gif', 'code' => ':heart'],
            ['cats' => 1, 'name' => 'hello.gif', 'code' => ':hello'],
            ['cats' => 1, 'name' => 'help.gif', 'code' => ':help'],
            ['cats' => 1, 'name' => 'help2.gif', 'code' => ':help2'],
            ['cats' => 1, 'name' => 'hi.gif', 'code' => ':hi'],
            ['cats' => 1, 'name' => 'infat.gif', 'code' => ':infat'],
            ['cats' => 1, 'name' => 'kiss.gif', 'code' => ':kiss'],
            ['cats' => 1, 'name' => 'kiss2.gif', 'code' => ':kiss2'],
            ['cats' => 1, 'name' => 'klass.gif', 'code' => ':klass'],
            ['cats' => 1, 'name' => 'krut.gif', 'code' => ':krut'],
            ['cats' => 1, 'name' => 'krutoy.gif', 'code' => ':krutoy'],
            ['cats' => 1, 'name' => 'ku.gif', 'code' => ':ku'],
            ['cats' => 1, 'name' => 'kuku.gif', 'code' => ':kuku'],
            ['cats' => 1, 'name' => 'kulak.gif', 'code' => ':kulak'],
            ['cats' => 1, 'name' => 'lamer.gif', 'code' => ':lamer'],
            ['cats' => 1, 'name' => 'love.gif', 'code' => ':love'],
            ['cats' => 1, 'name' => 'love2.gif', 'code' => ':love2'],
            ['cats' => 1, 'name' => 'mail.gif', 'code' => ':mail'],
            ['cats' => 1, 'name' => 'mister.gif', 'code' => ':mister'],
            ['cats' => 1, 'name' => 'money.gif', 'code' => ':money'],
            ['cats' => 1, 'name' => 'moped.gif', 'code' => ':moped'],
            ['cats' => 1, 'name' => 'musik.gif', 'code' => ':musik'],
            ['cats' => 1, 'name' => 'nea.gif', 'code' => ':nea'],
            ['cats' => 1, 'name' => 'net.gif', 'code' => ':net'],
            ['cats' => 1, 'name' => 'neznaju.gif', 'code' => ':neznaju'],
            ['cats' => 1, 'name' => 'ninja.gif', 'code' => ':ninja'],
            ['cats' => 1, 'name' => 'no.gif', 'code' => ':no'],
            ['cats' => 1, 'name' => 'nono.gif', 'code' => ':nono'],
            ['cats' => 1, 'name' => 'nozh.gif', 'code' => ':nozh'],
            ['cats' => 1, 'name' => 'nyam.gif', 'code' => ':nyam'],
            ['cats' => 1, 'name' => 'nyam2.gif', 'code' => ':icecream'],
            ['cats' => 1, 'name' => 'obana.gif', 'code' => ':obana'],
            ['cats' => 1, 'name' => 'ogogo.gif', 'code' => ':ogogo'],
            ['cats' => 1, 'name' => 'oops.gif', 'code' => ':oops'],
            ['cats' => 1, 'name' => 'opa.gif', 'code' => ':opa'],
            ['cats' => 1, 'name' => 'otstoy.gif', 'code' => ':otstoy'],
            ['cats' => 1, 'name' => 'oy.gif', 'code' => ':oy'],
            ['cats' => 1, 'name' => 'pirat.gif', 'code' => ':pirat'],
            ['cats' => 1, 'name' => 'pirat2.gif', 'code' => ':pirat2'],
            ['cats' => 1, 'name' => 'pistolet.gif', 'code' => ':pistolet'],
            ['cats' => 1, 'name' => 'pistolet2.gif', 'code' => ':pistolet2'],
            ['cats' => 1, 'name' => 'pizdec.gif', 'code' => ':shok3'],
            ['cats' => 1, 'name' => 'poisk.gif', 'code' => ':poisk'],
            ['cats' => 1, 'name' => 'proud.gif', 'code' => ':proud'],
            ['cats' => 1, 'name' => 'puls.gif', 'code' => ':puls'],
            ['cats' => 1, 'name' => 'queen.gif', 'code' => ':queen'],
            ['cats' => 1, 'name' => 'rap.gif', 'code' => ':rap'],
            ['cats' => 1, 'name' => 'read.gif', 'code' => ':read'],
            ['cats' => 1, 'name' => 'respekt.gif', 'code' => ':respekt'],
            ['cats' => 1, 'name' => 'rok.gif', 'code' => ':rok'],
            ['cats' => 1, 'name' => 'rok2.gif', 'code' => ':rok2'],
            ['cats' => 1, 'name' => 'senjor.gif', 'code' => ':senjor'],
            ['cats' => 1, 'name' => 'shok.gif', 'code' => ':shok'],
            ['cats' => 1, 'name' => 'shok2.gif', 'code' => ':shok2'],
            ['cats' => 1, 'name' => 'skull.gif', 'code' => ':skull'],
            ['cats' => 1, 'name' => 'smert.gif', 'code' => ':smert'],
            ['cats' => 1, 'name' => 'smoke.gif', 'code' => ':smoke'],
            ['cats' => 1, 'name' => 'spy.gif', 'code' => ':spy'],
            ['cats' => 1, 'name' => 'strela.gif', 'code' => ':strela'],
            ['cats' => 1, 'name' => 'svist.gif', 'code' => ':svist'],
            ['cats' => 1, 'name' => 'tiho.gif', 'code' => ':tiho'],
            ['cats' => 1, 'name' => 'vau.gif', 'code' => ':vau'],
            ['cats' => 1, 'name' => 'victory.gif', 'code' => ':victory'],
            ['cats' => 1, 'name' => 'visavi.gif', 'code' => ':visavi'],
            ['cats' => 1, 'name' => 'visavi2.gif', 'code' => ':visavi2'],
            ['cats' => 1, 'name' => 'vtopku.gif', 'code' => ':vtopku'],
            ['cats' => 1, 'name' => 'wackogirl.gif', 'code' => ':wackogirl'],
            ['cats' => 1, 'name' => 'xaxa.gif', 'code' => ':xaxa'],
            ['cats' => 1, 'name' => 'xmm.gif', 'code' => ':xmm'],
            ['cats' => 1, 'name' => 'yu.gif', 'code' => ':yu'],
            ['cats' => 1, 'name' => 'zlo.gif', 'code' => ':zlo'],
            ['cats' => 1, 'name' => 'ban.gif', 'code' => ':ban'],
            ['cats' => 1, 'name' => 'ban2.gif', 'code' => ':ban2'],
            ['cats' => 1, 'name' => 'banned.gif', 'code' => ':banned'],
            ['cats' => 1, 'name' => 'closed.gif', 'code' => ':closed'],
            ['cats' => 1, 'name' => 'closed2.gif', 'code' => ':closed2'],
            ['cats' => 1, 'name' => 'devil.gif', 'code' => ':devil'],
            ['cats' => 1, 'name' => 'flood.gif', 'code' => ':flood'],
            ['cats' => 1, 'name' => 'flood2.gif', 'code' => ':flood2'],
            ['cats' => 1, 'name' => 'huligan.gif', 'code' => ':huligan'],
            ['cats' => 1, 'name' => 'ment.gif', 'code' => ':ment'],
            ['cats' => 1, 'name' => 'ment2.gif', 'code' => ':ment2'],
            ['cats' => 1, 'name' => 'moder.gif', 'code' => ':moder'],
            ['cats' => 1, 'name' => 'nika.gif', 'code' => ':girlmoder'],
            ['cats' => 1, 'name' => 'offtop.gif', 'code' => ':offtop'],
            ['cats' => 1, 'name' => 'pravila.gif', 'code' => ':pravila'],
            ['cats' => 1, 'name' => 'zona.gif', 'code' => ':zona'],
            ['cats' => 1, 'name' => 'zub.gif', 'code' => ':zub'],
            ['cats' => 1, 'name' => 'crazy.gif', 'code' => ':crazy'],
            ['cats' => 1, 'name' => 'paratrooper.gif', 'code' => ':moder2'],
            ['cats' => 1, 'name' => 'bug.gif', 'code' => ':bug'],
            ['cats' => 1, 'name' => 'facepalm.gif', 'code' => ':facepalm'],
            ['cats' => 1, 'name' => 'wall.gif', 'code' => ':wall'],
            ['cats' => 1, 'name' => 'boss.gif', 'code' => ':boss'],

        ];

        $table->insert($data)->save();
    }
}