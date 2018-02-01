<?php

use Phinx\Seed\AbstractSeed;

class RulesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $this->execute('TRUNCATE rules');

        $data = [
            'id' => 1,
            'text' => 'Незнание этих Правил не только не освобождает Вас от ответственности за их нарушение, но и само по себе является нарушением!

[b]Общие правила для пользователей сайта %SITENAME%[/b]

[b]1. Общие положения:[/b]
а) Сайт посвящен вопросам призванным помочь wap/web-мастеру в разработке сайта, проекта или приложения для сайта.
б) На сайте строго запрещены обсуждения связанные с взломом сайтов, email адресов, ICQ-номеров (и прочего).
в) Все материалы и сообщения, размещаемые на сайте, отражают исключительно мнения их авторов, администрация сайта не дает каких-либо гарантий, выраженных явно, или подразумеваемых, что они полны, полезны или правдивы.

[b]2. Порядок поведения на сайте:[/b]
а) Публикация ссылок на другие сайты допустима исключительно при условии, что страница, находящаяся по указанному адресу имеет непосредственное отношение к теме, приведена в качестве иллюстрации утверждения, высказанного автором сообщения.
б) На сайте применяется пост-модерация. Сообщения, нарушающие настоящие правила, удаляются. Не следует воспринимать исчезновение своих сообщений следствием технического сбоя и помещать сообщения еще раз.
в) Не одобряются попытки обратить внимание на низкий уровень знаний какого-либо участника сайта. Все когда-то не знали простых вещей.
г) Вы обязаны соблюдать уважительное отношение к собеседнику, правильное (грамотное) и доходчивое изложение мыслей и фактов.
д) Не обращайте внимания на маргиналов и прочих брутальных личностей. Не дразните и не подначивайте их - отсутствие внимания сразу сводит дискуссию на нет. Не стоит отвечать им той же монетой, даже если Вы считаете, что Вас оскорбили. Остальное - забота администрации сайта.
е) Если Вы видите сообщение, нарушающее любое правило сайта, сообщите об этом администрации в "Приват", не стоит об этом кричать на форуме во всеуслышание.

[b]3. При создании новых тем в форуме необходимо придерживаться следующих правил:[/b]
а) Название темы должно быть информативным. Заголовки тем типа: "Подскажите", "Знающие люди, зайдите!", "Есть вопрос", "Вопрос по php-коду" и подобные, лишь демонстрируют Ваше неуважение к остальным посетителям сайта.
б) Тема должна соответствовать теме раздела, в котором она находится. Не следует открывать тему в определенном разделе только потому, что Вы хотите получить быстрый ответ в более посещаемом разделе.
в) Запрещается создание тем обращенных к конкретным участникам конференции (для этого существует "Приват").
г) Запрещается продолжение обсуждений вопросов из тем, закрытых/удалённых администрацией. Перед тем как задать вопрос, настоятельно рекомендуем пользоваться поиском по форуму, наверняка Ваш вопрос уже обсуждался ранее.

[b]4. Запрещается помещение сообщений, содержащих:[/b]
а) Призывы к нарушению действующего законодательства, высказывания расистского характера, разжигание межнациональной розни, нагнетание обстановки на форуме и всего прочего, что попадает под действие УК РФ.
б) Грубые, нецензурные выражения и оскорбления в любой форме (флейм) - сообщения, грубые по тону, содержащие "наезды" на личности.
в) Бессмысленную или малосодержательную информацию, которая не несет смысловой нагрузки - пустую болтовню (флуд).
г) Оффтоп, т.е. уход от основного обсуждения в рамках отдельной темы.
д) Ложную информацию, клевету, а также нечестные приемы ведения дискуссий в виде "передергиваний" высказываний собеседников.
е) Откровенное рекламное содержание, в том числе с просьбой "Посетите/оцените мой сайт".
ж) Безосновательные утверждения, что "это" лучше, а "это" хуже, а также глупые советы типа "выпей йаду", "полюби гугл" и т.д.
з) Чрезмерное количество грамматических ошибок и жаргонных слов.
и) Обсуждение и выражение своих недовольств к действиям модераторов форума. Для этого существует "Приват".

За выполнением требований правил следит администрация, а также специально назначенные модераторы. Администрация имеет право не предупреждать пользователей о принимаемых мерах.

[b]5. Копирование или любое несанкционированное использование материалов сайта запрещено.[/b]

[b][color=#ff0000]Внимание! Если пользователь пренебрегает данными Правилами, его аккуант блокируется.
Если пользователь систематически игнорирует предупреждения администрации, то его учётная запись может быть удалена.[/color][/b]',
            'created_at' => SITETIME,
        ];

        $table = $this->table('rules');
        $table->insert($data)->save();
    }
}
