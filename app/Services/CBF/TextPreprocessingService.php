<?php

namespace App\Services\CBF;

use App\Models\PermintaanPembimbing;
use App\Models\ProfileDosen;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;
use Sastrawi\Stemmer\StemmerFactory;

class TextPreprocessingService
{
    protected $stemmer;
    protected array $stopwordsIndonesia = ["ada", "adanya", "adalah", "adapun", "agak", "agaknya", "agar", "akan", "akankah", "akhirnya", "aku", "akulah", "amat", "amatlah", "anda", "andalah", "antar", "diantaranya", "antara", "antaranya", "diantara", "apa", "apaan", "mengapa", "apabila", "apakah", "apalagi", "apatah", "atau", "ataukah", "ataupun", "bagai", "bagaikan", "sebagai", "sebagainya", "bagaimana", "bagaimanapun", "sebagaimana", "bagaimanakah", "bagi", "bahkan", "bahwa", "bahwasanya", "sebaliknya", "banyak", "sebanyak", "beberapa", "seberapa", "begini", "beginian", "beginikah", "beginilah", "sebegini", "begitu", "begitukah", "begitulah", "begitupun", "sebegitu", "belum", "belumlah", "sebelum", "sebelumnya", "sebenarnya", "berapa", "berapakah", "berapalah", "berapapun", "betulkah", "sebetulnya", "biasa", "biasanya", "bila", "bilakah", "bisa", "bisakah", "sebisanya", "boleh", "bolehkah", "bolehlah", "buat", "bukan", "bukankah", "bukanlah", "bukannya", "cuma", "percuma", "dahulu", "dalam", "dan", "dapat", "dari", "daripada", "dekat", "demi", "demikian", "demikianlah", "sedemikian", "dengan", "depan", "di", "dia", "dialah", "dini", "diri", "dirinya", "terdiri", "dong", "dulu", "enggak", "enggaknya", "entah", "entahlah", "terhadap", "terhadapnya", "hal", "hampir", "hanya", "hanyalah", "harus", "haruslah", "harusnya", "seharusnya", "hendak", "hendaklah", "hendaknya", "hingga", "sehingga", "ia", "ialah", "ibarat", "ingin", "inginkah", "inginkan", "ini", "inikah", "inilah", "itu", "itukah", "itulah", "jangan", "jangankan", "janganlah", "jika", "jikalau", "juga", "justru", "kala", "kalau", "kalaulah", "kalaupun", "kalian", "kami", "kamilah", "kamu", "kamulah", "kan", "kapan", "kapankah", "kapanpun", "dikarenakan", "karena", "karenanya", "ke", "kecil", "kemudian", "kenapa", "kepada", "kepadanya", "ketika", "seketika", "khususnya", "kini", "kinilah", "kiranya", "sekiranya", "kita", "kitalah", "kok", "lagi", "lagian", "selagi", "lah", "lain", "lainnya", "melainkan", "selaku", "lalu", "melalui", "terlalu", "lama", "lamanya", "selama", "selamanya", "lebih", "terlebih", "bermacam", "macam", "semacam", "maka", "makanya", "makin", "malah", "malahan", "mampu", "mampukah", "mana", "manakala", "manalagi", "masih", "masihkah", "semasih", "masing", "mau", "maupun", "semaunya", "memang", "mereka", "merekalah", "meski", "meskipun", "semula", "mungkin", "mungkinkah", "nah", "namun", "nanti", "nantinya", "nyaris", "oleh", "olehnya", "seorang", "seseorang", "pada", "padanya", "padahal", "paling", "sepanjang", "pantas", "sepantasnya", "sepantasnyalah", "para", "pasti", "pastilah", "per", "pernah", "pula", "pun", "merupakan", "rupanya", "serupa", "saat", "saatnya", "sesaat", "saja", "sajalah", "saling", "bersama", "sama", "sesama", "sambil", "sampai", "sana", "sangat", "sangatlah", "saya", "sayalah", "se", "sebab", "sebabnya", "sebuah", "tersebut", "tersebutlah", "sedang", "sedangkan", "sedikit", "sedikitnya", "segala", "segalanya", "segera", "sesegera", "sejak", "sejenak", "sekali", "sekalian", "sekalipun", "sesekali", "sekaligus", "sekarang", "sekitar", "sekitarnya", "sela", "selain", "selalu", "seluruh", "seluruhnya", "semakin", "sementara", "sempat", "semua", "semuanya", "sendiri", "sendirinya", "seolah", "seperti", "sepertinya", "sering", "seringnya", "serta", "siapa", "siapakah", "siapapun", "disini", "disinilah", "sini", "sinilah", "sesuatu", "sesuatunya", "suatu", "sesudah", "sesudahnya", "sudah", "sudahkah", "sudahlah", "supaya", "tadi", "tadinya", "tak", "tanpa", "setelah", "telah", "tentang", "tentu", "tentulah", "tentunya", "tertentu", "seterusnya", "tapi", "tetapi", "setiap", "tiap", "setidaknya", "tidak", "tidakkah", "tidaklah", "toh", "waduh", "wah", "wahai", "sewaktu", "walau", "walaupun", "wong", "yaitu", "yakni", "yang"];
    protected array $stopwordsEnglish = ["able", "about", "above", "abroad", "according", "accordingly", "across", "actually", "adj", "after", "afterwards", "again", "against", "ago", "ahead", "ain't", "all", "allow", "allows", "almost", "alone", "along", "alongside", "already", "also", "although", "always", "am", "amid", "amidst", "among", "amongst", "an", "and", "another", "any", "anybody", "anyhow", "anyone", "anything", "anyway", "anyways", "anywhere", "apart", "appear", "appreciate", "appropriate", "are", "aren't", "around", "as", "a's", "aside", "ask", "asking", "associated", "at", "available", "away", "awfully", "back", "backward", "backwards", "be", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "begin", "behind", "being", "believe", "below", "beside", "besides", "best", "better", "between", "beyond", "both", "brief", "but", "by", "came", "can", "cannot", "cant", "can't", "caption", "cause", "causes", "certain", "certainly", "changes", "clearly", "c'mon", "co", "co.", "com", "come", "comes", "concerning", "consequently", "consider", "considering", "contain", "containing", "contains", "corresponding", "could", "couldn't", "course", "c's", "currently", "dare", "daren't", "definitely", "described", "despite", "did", "didn't", "different", "directly", "do", "does", "doesn't", "doing", "done", "don't", "down", "downwards", "during", "each", "edu", "eg", "eight", "eighty", "either", "else", "elsewhere", "end", "ending", "enough", "entirely", "especially", "et", "etc", "even", "ever", "evermore", "every", "everybody", "everyone", "everything", "everywhere", "ex", "exactly", "example", "except", "fairly", "far", "farther", "few", "fewer", "fifth", "first", "five", "followed", "following", "follows", "for", "forever", "former", "formerly", "forth", "forward", "found", "four", "from", "further", "furthermore", "get", "gets", "getting", "given", "gives", "go", "goes", "going", "gone", "got", "gotten", "greetings", "had", "hadn't", "half", "happens", "hardly", "has", "hasn't", "have", "haven't", "having", "he", "he'd", "he'll", "hello", "help", "hence", "her", "here", "hereafter", "hereby", "herein", "here's", "hereupon", "hers", "herself", "he's", "hi", "him", "himself", "his", "hither", "hopefully", "how", "howbeit", "however", "hundred", "i'd", "ie", "if", "ignored", "i'll", "i'm", "immediate", "in", "inasmuch", "inc", "inc.", "indeed", "indicate", "indicated", "indicates", "inner", "inside", "insofar", "instead", "into", "inward", "is", "isn't", "it", "it'd", "it'll", "its", "it's", "itself", "i've", "just", "k", "keep", "keeps", "kept", "know", "known", "knows", "last", "lately", "later", "latter", "latterly", "least", "less", "lest", "let", "let's", "like", "liked", "likely", "likewise", "little", "look", "looking", "looks", "low", "lower", "ltd", "made", "mainly", "make", "makes", "many", "may", "maybe", "mayn't", "me", "mean", "meantime", "meanwhile", "merely", "might", "mightn't", "mine", "minus", "miss", "more", "moreover", "most", "mostly", "mr", "mrs", "much", "must", "mustn't", "my", "myself", "name", "namely", "nd", "near", "nearly", "necessary", "need", "needn't", "needs", "neither", "never", "neverf", "neverless", "nevertheless", "new", "next", "nine", "ninety", "no", "nobody", "non", "none", "nonetheless", "noone", "no-one", "nor", "normally", "not", "nothing", "notwithstanding", "novel", "now", "nowhere", "obviously", "of", "off", "often", "oh", "ok", "okay", "old", "on", "once", "one", "ones", "one's", "only", "onto", "opposite", "or", "other", "others", "otherwise", "ought", "oughtn't", "our", "ours", "ourselves", "out", "outside", "over", "overall", "own", "particular", "particularly", "past", "per", "perhaps", "placed", "please", "plus", "possible", "presumably", "probably", "provided", "provides", "que", "quite", "qv", "rather", "rd", "re", "really", "reasonably", "recent", "recently", "regarding", "regardless", "regards", "relatively", "respectively", "right", "round", "said", "same", "saw", "say", "saying", "says", "second", "secondly", "see", "seeing", "seem", "seemed", "seeming", "seems", "seen", "self", "selves", "sensible", "sent", "serious", "seriously", "seven", "several", "shall", "shan't", "she", "she'd", "she'll", "she's", "should", "shouldn't", "since", "six", "so", "some", "somebody", "someday", "somehow", "someone", "something", "sometime", "sometimes", "somewhat", "somewhere", "soon", "sorry", "specified", "specify", "specifying", "still", "sub", "such", "sup", "sure", "take", "taken", "taking", "tell", "tends", "th", "than", "thank", "thanks", "thanx", "that", "that'll", "thats", "that's", "that've", "the", "their", "theirs", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "there'd", "therefore", "therein", "there'll", "there're", "theres", "there's", "thereupon", "there've", "these", "they", "they'd", "they'll", "they're", "they've", "thing", "things", "think", "third", "thirty", "this", "thorough", "thoroughly", "those", "though", "three", "through", "throughout", "thru", "thus", "till", "to", "together", "too", "took", "toward", "towards", "tried", "tries", "truly", "try", "trying", "t's", "twice", "two", "un", "under", "underneath", "undoing", "unfortunately", "unless", "unlike", "unlikely", "until", "unto", "up", "upon", "upwards", "us", "use", "used", "useful", "uses", "using", "usually", "v", "value", "various", "versus", "very", "via", "viz", "vs", "want", "wants", "was", "wasn't", "way", "we", "we'd", "welcome", "well", "we'll", "went", "were", "we're", "weren't", "we've", "what", "whatever", "what'll", "what's", "what've", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "where's", "whereupon", "wherever", "whether", "which", "whichever", "while", "whilst", "whither", "who", "who'd", "whoever", "whole", "who'll", "whom", "whomever", "who's", "whose", "why", "will", "willing", "wish", "with", "within", "without", "wonder", "won't", "would", "wouldn't", "yes", "yet", "you", "you'd", "you'll", "your", "you're", "yours", "yourself", "yourselves", "you've", "zero", "a", "how's", "i", "when's", "why's", "b", "c", "d", "e", "f", "g", "h", "j", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "uucp", "w", "x", "y", "z", "I", "www", "amount", "bill", "bottom", "call", "computer", "con", "couldnt", "cry", "de", "describe", "detail", "due", "eleven", "empty", "fifteen", "fifty", "fill", "find", "fire", "forty", "front", "full", "give", "hasnt", "herse", "himse", "interest", "itse”", "mill", "move", "myse”", "part", "put", "show", "side", "sincere", "sixty", "system", "ten", "thick", "thin", "top", "twelve", "twenty", "abst", "accordance", "act", "added", "adopted", "affected", "affecting", "affects", "ah", "announce", "anymore", "apparently", "approximately", "aren", "arent", "arise", "auth", "beginning", "beginnings", "begins", "biol", "briefly", "ca", "date", "ed", "effect", "et-al", "ff", "fix", "gave", "giving", "heres", "hes", "hid", "home", "id", "im", "immediately", "importance", "important", "index", "information", "invention", "itd", "keys", "kg", "km", "largely", "lets", "line", "'ll", "means", "mg", "million", "ml", "mug", "na", "nay", "necessarily", "nos", "noted", "obtain", "obtained", "omitted", "ord", "owing", "page", "pages", "poorly", "possibly", "potentially", "pp", "predominantly", "present", "previously", "primarily", "promptly", "proud", "quickly", "ran", "readily", "ref", "refs", "related", "research", "resulted", "resulting", "results", "run", "sec", "section", "shed", "shes", "showed", "shown", "showns", "shows", "significant", "significantly", "similar", "similarly", "slightly", "somethan", "specifically", "state", "states", "stop", "strongly", "substantially", "successfully", "sufficiently", "suggest", "thered", "thereof", "therere", "thereto", "theyd", "theyre", "thou", "thoughh", "thousand", "throug", "til", "tip", "ts", "ups", "usefully", "usefulness", "'ve", "vol", "vols", "wed", "whats", "wheres", "whim", "whod", "whos", "widely", "words", "world", "youd", "youre"];

    public function __construct()
    {
        $this->stemmer = (new StemmerFactory())->createStemmer();
    }


    public function getData(int $permintaanPembimbingId): array
    {
        $permintaanPembimbing = PermintaanPembimbing::with(['mataKuliah', 'mahasiswa'])
            ->whereKey($permintaanPembimbingId)
            ->first();

        if (! $permintaanPembimbing) {
            throw new InvalidArgumentException('Data permintaan pembimbing tidak ditemukan.');
        }

        $dosens = ProfileDosen::with(['publikasi', 'mataKuliah'])->get();

        return [
            'permintaan_pembimbing_id' => $permintaanPembimbing->id,
            'mahasiswa_id' => $permintaanPembimbing->mahasiswa_id,
            'judul_ta' => $this->buildStudentDocument($permintaanPembimbing),
            'publikasi_dosen' => $this->buildLecturerDocuments($dosens),
        ];
    }

    protected function buildStudentDocument(PermintaanPembimbing $permintaanPembimbing): string
    {
        $mataKuliah = $permintaanPembimbing->mataKuliah
            ->map(fn($item) => trim($item->nama))
            ->implode(' ');

        return trim(implode(' ', array_filter([
            $permintaanPembimbing->judul_ta,
            $mataKuliah,
        ])));
    }

    protected function buildLecturerDocuments(Collection $dosens): array
    {
        $documents = [];

        foreach ($dosens as $dosen) {
            $mataKuliah = $dosen->mataKuliah
                ->map(fn($item) => trim($item->nama))
                ->implode(' ');

            $publikasi = $dosen->publikasi
                ->map(fn($publikasi) => trim(implode(' ', array_filter([
                    $publikasi->judul,
                    $publikasi->abstrak ?? null,
                ]))))
                ->filter()
                ->implode(' ');

            $documents[$dosen->id] = trim(implode(' ', array_filter([
                $dosen->keahlian,
                $mataKuliah,
                $publikasi,
            ])));
        }

        return $documents;
    }

    public function preprocessing(int $permintaanPembimbingId): array
    {
        $data = $this->getData($permintaanPembimbingId);

        $judulTaTokens = $this->processText($data['judul_ta']);

        $publikasiTokens = [];

        foreach ($data['publikasi_dosen'] as $dosenId => $teks) {
            if ($data['publikasi_dosen'][$dosenId] != "") {
                $publikasiTokens[$dosenId] = $this->processText($teks);
            }
        }

        return [
            'judul_ta' => $judulTaTokens,
            'publikasi_dosen' => $publikasiTokens
        ];
    }

    /**
     * Pipeline preprocessing teks
     */
    protected function processText(string $text): array
    {
        if (empty(trim($text))) {
            return [];
        }

        // 1. Case Folding
        $text = strtolower($text);

        // 2. Cleaning
        $text = preg_replace('/[^a-z\s]/', ' ', $text);

        // 3. Normalisasi whitespace
        $text = preg_replace('/\s+/', ' ', trim($text));

        // 4. Tokenizing
        $tokens = explode(' ', $text);

        // 5. Stopword Removal (Indonesia)
        $tokens = array_filter($tokens, function ($token) {
            return !in_array($token, $this->stopwordsIndonesia, true);
        });

        // 6. Stopword Removal (English)
        $tokens = array_filter($tokens, function ($token) {
            return !in_array($token, $this->stopwordsEnglish, true);
        });

        // 7. Stemming
        $tokens = array_map(function ($token) {
            return $this->stemmer->stem($token);
        }, $tokens);

        // 8. Filter kata pendek (< 3 karakter)
        $tokens = array_filter($tokens, function ($token) {
            return strlen($token) >= 3;
        });

        // 9. Re-index array
        return array_values($tokens);
    }
}
