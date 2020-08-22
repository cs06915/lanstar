<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
class contents{
    public static function parseContent($data, $widget, $last)
    {
        $text = empty($last) ? $data : $last;
        if ($widget instanceof Widget_Archive) {
            $text = contents::parseCode($text);
            //owo
            $text = contents::parseOwo($text);
            // 友链解析
            $text = contents::parseLink($text);
        }
        return $text;
    }
    /**
     *  解析 owo 表情
     */
    public static function parseOwo($content) {
        $content = preg_replace_callback('/\:\:\(\s*(呵呵|哈哈|吐舌|太开心|笑眼|花心|小乖|乖|捂嘴笑|滑稽|你懂的|不高兴|怒|汗|黑线|泪|真棒|喷|惊哭|阴险|鄙视|酷|啊|狂汗|what|疑问|酸爽|呀咩爹|委屈|惊讶|睡觉|笑尿|挖鼻|吐|犀利|小红脸|懒得理|勉强|爱心|心碎|玫瑰|礼物|彩虹|太阳|星星月亮|钱币|茶杯|蛋糕|大拇指|胜利|haha|OK|沙发|手纸|香蕉|便便|药丸|红领巾|蜡烛|音乐|灯泡|开心|钱|咦|呼|冷|生气|弱|吐血)\s*\)/is',
            array('contents', 'parsePaopaoBiaoqingCallback'), $content);
        $content = preg_replace_callback('/\:\@\(\s*(高兴|小怒|脸红|内伤|装大款|赞一个|害羞|汗|吐血倒地|深思|不高兴|无语|亲亲|口水|尴尬|中指|想一想|哭泣|便便|献花|皱眉|傻笑|狂汗|吐|喷水|看不见|鼓掌|阴暗|长草|献黄瓜|邪恶|期待|得意|吐舌|喷血|无所谓|观察|暗地观察|肿包|中枪|大囧|呲牙|抠鼻|不说话|咽气|欢呼|锁眉|蜡烛|坐等|击掌|惊喜|喜极而泣|抽烟|不出所料|愤怒|无奈|黑线|投降|看热闹|扇耳光|小眼睛|中刀)\s*\)/is',
            array('contents', 'parseAruBiaoqingCallback'), $content);
        $content = preg_replace_callback('/\:\&\(\s*(.*?)\s*\)/is',
            array('contents', 'parseQuyinBiaoqingCallback'), $content);

        return $content;
    }
    /**
     * 泡泡表情回调函数
     *
     * @return string
     */
    public static function parsePaopaoBiaoqingCallback($match)
    {
        return '<img class="biaoqing" src="/usr/themes/lanstar/assets/owo/biaoqing/paopao/'. str_replace('%', '', urlencode($match[1])) . '_2x.png">';
    }

    /**
     * 阿鲁表情回调函数
     *
     * @return string
     */
    public static function parseAruBiaoqingCallback($match)
    {
        return '<img class="biaoqing" src="/usr/themes/lanstar/assets/owo/biaoqing/aru/'. str_replace('%', '', urlencode($match[1])) . '_2x.png">';
    }

    /**
     * 蛆音娘表情回调函数
     *
     * @return string
     */
    public static function parseQuyinBiaoqingCallback($match)
    {
        return '<img class="biaoqing" src="/usr/themes/lanstar/assets/owo/biaoqing/quyin/'. str_replace('%', '', urlencode($match[1])) . '.png">';
    }
    /**
     * 解析代码块
     */
    public static function parseCode($text) {
        $text = preg_replace('/<pre><code>/s','<pre><code class="language-html">',$text);
        return $text;
    }
    /**
     * 友链解析
     */
    public static function parseLink($text) {
        $reg = '/\[links\](.*?)\[\/links\]/s';
        if (preg_match($reg, $text)) {
            $rp = '<div class="links-box container-fluid"><div class="row">${1}</div></div>';
            $text = preg_replace($reg, $rp, $text);
            $pattern = '/\[(.*?)\]\((.*?)\)\+\((.*)\)/';
            $replacement = '<div class="col-lg-3 col-6 col-md-4 links-container">
		    <a href="${2}" title="${1}" target="_blank" class="links-link">
			  <div class="links-item">
			    <div class="links-img"><img src="${3}"></div>
				<div class="links-title">
				  <h4>${1}</h4>
				</div>
		      </div>
			  </a>
			</div>';
            return preg_replace($pattern, $replacement, $text);
        }else{
            return $text;
        }
    }

}