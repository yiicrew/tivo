<?php

namespace app\components;

use Yii;
use \Exception;
use yii\base\Component;

class Imdb extends Component
{
    public $posterPath = "";
    public $cachePath = "";

    private $_imdb = null;

    public function getMovie($url)
    {
        $imdb = new IMDBClient($url, [
            'basePath' => Yii::$app->basePath . '/runtime'
        ]);
        return $imdb;
    }
}

/**
 * php-imdb
 *
 * This PHP library serves as a simple interface to imdb.
 *
 *
 * @author  allyraza <saytoally@hotmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License
 * @link    https://github.com/allyraza/php-imdb GitHub Repository
 * @version 0.1
 */
class IMDBClient
{
    /**
     * Set this to true if you run into problems.
     */
    public $debug = false;

    /**
     * Set the preferred language for the User Agent.
     */
    public $language = 'en, en-US;q=0.8';

    /**
     * Define the timeout for cURL requests.
     */
    public $requestTimeout = 15;

    /**
     * @var int Maximum cache time.
     */
    private $cacheTimeout = 1440;

    /**
     * @var null|string The basePath of the script.
     */
    private $basePath = null;

    /**
     * @var null|string Holds the source.
     */
    private $source = null;

    /**
     * @var null|int The ID of the movie.
     */
    public $id = null;

    /**
     * @var string What to search for?
     */
    private $query = 'all';

    /**
     * @var bool Is the content ready?
     */
    public $isReady = false;

    /**
     * @var string The string returned, if nothing is found.
     */
    public $notFound = 'N/A';

    /**
     * @var string Char that separates multiple entries.
     */
    public $separator = ' / ';

    /**
     * @var null|string The URL to the movie.
     */
    public $url = null;

    /**
     * @var bool Return responses enclosed in array
     */
    public $arrayOutput = false;

    /**
     * These are the regular expressions used to extract the data.
     * If you don’t know what you’re doing, you shouldn’t touch them.
     */
    const IMDB_AKA = '~<h5>Also Known As:<\/h5>(?:\s*)<div class="info-content">(?:\s*)"(.*)"~Ui';
    const IMDB_ASPECT_RATIO = '~<h5>Aspect Ratio:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_AWARDS = '~<h5>Awards:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_CAST = '~<td class="nm"><a href="\/name\/(.*)\/"(?:.*)>(.*)<\/a><\/td>~Ui';
    const IMDB_CERTIFICATION = '~<h5>Certification:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_CHAR = '~<td class="char">(.*)<\/td>~Ui';
    const IMDB_COLOR = '~<h5>Color:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_COMPANY = '~<h5>Company:<\/h5>(?:\s*)<div class="info-content"><a href="\/company\/(.*)\/">(.*)</a>(?:.*)<\/div>~Ui';
    const IMDB_COUNTRY = '~<a href="/country/(\w+)">(.*)</a>~Ui';
    const IMDB_CREATOR = '~<h5>(?:Creator|Creators):<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_DIRECTOR = '~<h5>(?:Director|Directors):<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_GENRE = '~<a href="\/Sections\/Genres\/([a-zA-Z_][\w-]*+)\/">(.*)<\/a>~Ui';
    const IMDB_ID = '~((?:tt\d{6,})|(?:itle\?\d{6,}))~';
    const IMDB_LANGUAGE = '~<a href="\/language\/(\w+)">(.*)<\/a>~Ui';
    const IMDB_LOCATION = '~href="\/search\/title\?locations=(.*)">(.*)<\/a>~Ui';
    const IMDB_MPAA = '~<h5><a href="\/mpaa">MPAA<\/a>:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_NAME = '~href="\/name\/(.*)\/"(?:.*)>(.*)<\/a>~Ui';
    const IMDB_NOT_FOUND = '~<h1 class="findHeader">No results found for ~Ui';
    const IMDB_PLOT = '~<h5>Plot:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_PLOT_KEYWORDS = '~<h5>Plot Keywords:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_POSTER = '~<link rel="image_src" href="(.*)">~Ui';
    const IMDB_RATING = '~<div class="starbar-meta">(?:\s*)<b>(.*)\/10<\/b>~Ui';
    const IMDB_RELEASE_DATE = '~<h5>Release Date:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_RUNTIME = '~<h5>Runtime:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_SEARCH = '~<td class="result_text"> <a href="\/title\/(tt\d{6,})\/(?:.*)"(?:\s*)>(?:.*)<\/a>~Ui';
    const IMDB_SEASONS = '~episodes\?season=(?:\d+)">(\d+)<~Ui';
    const IMDB_SOUND_MIX = '~<h5>Sound Mix:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_TAGLINE = '~<h5>Tagline:<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_TITLE = '~property="og:title" content="(.*)"~Ui';
    const IMDB_TITLE_ORIG = '~<span class="title-extra">(.*) <i>\(original title\)<\/i></span>~Ui';
    const IMDB_TRAILER = '~data-video="(.*)"~Ui';
    const IMDB_URL = '~http://(?:.*\.|.*)imdb.com/(?:t|T)itle(?:\?|/)(..\d+)~i';
    const IMDB_USER_REVIEW = '~<h5>User Reviews:<\/h5>(?:\s*)<div class="info-content">(.*)<a~Ui';
    const IMDB_VOTES = '~<a href="ratings" class="tn15more">(.*) votes<\/a>~Ui';
    const IMDB_WRITER = '~<h5>(?:Writer|Writers):<\/h5>(?:\s*)<div class="info-content">(.*)<\/div>~Ui';
    const IMDB_YEAR = '~content="(?:.*)\(*(\d{4})\)~Ui';

    /**
     * @param string $search    IMDb URL or movie title to search for.
     * @param null   $cacheTimeout     Custom cache time in minutes.
     * @param string $query What to search for?
     *
     * @throws \Exception
     */
    public function __construct($search, $opts)
    {
        $query = isset($opts['query']) ? $opts['query'] : 'all';

        if (isset($opts['basePath'])) {
            $this->basePath = $opts['basePath'];
        } else {
            $this->basePath = dirname(__FILE__);
        }

        if (!is_writable($this->basePath . '/posters') && !mkdir($this->basePath . '/posters')) {
            throw new Exception('The directory “' . $this->basePath . '/posters” isn’t writable.');
        }

        if (!is_writable($this->basePath . '/cache') && !mkdir($this->basePath . '/cache')) {
            throw new Exception('The directory “' . $this->basePath . '/cache” isn’t writable.');
        }

        if (!function_exists('curl_init')) {
            throw new Exception('You need to enable the PHP cURL extension.');
        }

        $types = ['movie', 'tv', 'episode', 'game', 'all'];
        if (in_array($query, $types)) {
            $this->query = $query;
        }

        if (true === $this->debug) {
            echo '<pre><b>Running:</b> fetchUrl("' . $search . '")</pre>';
        }

        if (isset($opts['cacheTimeout']) && (int) $opts['cacheTimeout'] > 0) {
            $this->cacheTimeout = (int) $opts['cacheTimeout'];
        }

        $this->fetchUrl($search);
    }

    public function setBasePath($path)
    {
        $this->basePath = $path;
    }

    /**
     * @param string $search IMDb URL or movie title to search for.
     *
     * @return bool True on success, false on failure.
     */
    private function fetchUrl($query)
    {
        $query = trim($query);

        // Try to find a valid URL.
        $id = $this->matchRegex($query, static::IMDB_ID, 1);
        if ($id !== false) {
            $this->id = preg_replace('~[\D]~', '', $id);
            $this->url = 'http://www.imdb.com/title/tt' . $this->id . '/combined';
            $isSearch = false;
        } else {
            switch (strtolower($this->query)) {
                case 'movie':
                    $params = '&s=tt&ttype=ft';
                    break;
                case 'tv':
                    $params = '&s=tt&ttype=tv';
                    break;
                case 'episode':
                    $params = '&s=tt&ttype=ep';
                    break;
                case 'game':
                    $params = '&s=tt&ttype=vg';
                    break;
                default:
                    $params = '&s=tt';
            }

            $this->url = 'http://www.imdb.com/find?q=' . str_replace(' ', '+', $query) . $params;
            $isSearch = true;

            // Was this search already performed and cached?
            $redirectFile = $this->basePath . '/cache/' . md5($this->url) . '.redir';
            if (is_readable($redirectFile)) {
                if ($this->debug) {
                    echo '<pre><b>Using redirect:</b> ' . basename($redirectFile) . '</pre>';
                }
                $redirect = file_get_contents($redirectFile);
                $this->url = trim($redirect);
                $this->id = preg_replace('~[\D]~', '', $this->matchRegex($redirect, static::IMDB_ID, 1));
                $isSearch = false;
            }
        }

        // Does a cache of this movie exist?
        $cacheFile = $this->basePath . '/cache/' . md5($this->id) . '.cache';
        if (is_readable($cacheFile)) {
            $iDiff = round(abs(time() - filemtime($cacheFile)) / 60);
            if ($iDiff < $this->cacheTimeout) {
                if ($this->debug === true) {
                    echo '<pre><b>Using cache:</b> ' . basename($cacheFile) . '</pre>';
                }
                $this->source = file_get_contents($cacheFile);
                $this->isReady = true;

                return true;
            }
        }

        // Run cURL on the URL.
        if ($this->debug === true) {
            echo '<pre><b>Running cURL:</b> ' . $this->url . '</pre>';
        }

        $curlInfo = $this->runCurl($this->url);
        $source = $curlInfo['contents'];

        if ($source === false) {
            if ($this->debug === true) {
                echo '<pre><b>cURL error:</b> ' . var_dump($curlInfo) . '</pre>';
            }

            return false;
        }

        // Was the movie found?
        $match = $this->matchRegex($source, static::IMDB_SEARCH, 1);
        if ($match !== false) {
            $url = 'http://www.imdb.com/title/' . $match . '/combined';
            if ($this->debug === true) {
                echo '<pre><b>New redirect saved:</b> ' . basename($redirectFile) . ' => ' . $url . '</pre>';
            }
            file_put_contents($redirectFile, $url);
            $this->source = null;
            static::fetchUrl($url);

            return true;
        }
        $match = $this->matchRegex($source, static::IMDB_NOT_FOUND, 0);
        if ($match !== false) {
            if (true === $this->debug) {
                echo '<pre><b>Movie not found:</b> ' . $query . '</pre>';
            }

            return false;
        }

        $this->source = str_replace(["\n", "\r\n", "\r"], '', $source);
        $this->isReady = true;

        // Save cache.
        if ($isSearch === false) {
            if ($this->debug === true) {
                echo '<pre><b>Cache created:</b> ' . basename($cacheFile) . '</pre>';
            }
            file_put_contents($cacheFile, $this->source);
        }

        return true;
    }

    /**
     * @return string “Also Known As” or $notFound.
     */
    public function getAka()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_AKA, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * Returns all local names
     *
     * @return string The aka name.
     */
    public function getAkas()
    {
        if (true === $this->isReady) {
            // Does a cache of this movie exist?
            $cacheFile = $this->basePath . '/cache/' . md5($this->id) . '_akas.cache';
            $bUseCache = false;

            if (is_readable($cacheFile)) {
                $iDiff = round(abs(time() - filemtime($cacheFile)) / 60);
                if ($iDiff < $this->cacheTimeout || false) {
                    $bUseCache = true;
                }
            }

            if ($bUseCache) {
                $aRawReturn = file_get_contents($cacheFile);
                $result = unserialize($aRawReturn);

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
            } else {
                $fullAkas = sprintf('http://www.imdb.com/title/tt%s/releaseinfo', $this->id);
                $curlInfo = static::runCurl($fullAkas);
                $source = $curlInfo['contents'];

                if (false === $source) {
                    if (true === $this->debug) {
                        echo '<pre><b>cURL error:</b> ' . var_dump($curlInfo) . '</pre>';
                    }

                    return false;
                }

                $result = $this->matchRegex($source, "~<td>(.*?)<\/td>\s+<td>(.*?)<\/td>~");

                if ($result) {
                    $result = [];
                    foreach ($result[1] as $i => $strName) {
                        if (strpos($strName, '(') === false) {
                            $result[] = ['title' => $this->cleanString($result[2][$i]),
                                'country' => $this->cleanString($strName)];
                        }
                    }

                    file_put_contents($cacheFile, serialize($result));

                    return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
                }
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @return string “Aspect Ratio” or $notFound.
     */
    public function getAspectRatio()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_ASPECT_RATIO, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string The awards of the movie or $notFound.
     */
    public function getAwards()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_AWARDS, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param int  $limit How many cast members should be returned?
     * @param bool $bMore  Add … if there are more cast members than printed.
     *
     * @return string A list with cast members or $notFound.
     */
    public function getCast($limit = 0, $more = true)
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_CAST);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    if ($limit !== 0 && $i >= $limit) {
                        break;
                    }
                    $result[] = $this->cleanString($sName);
                }

                $more = (0 !== $limit && $more && (count($match[2]) > $limit) ? '…' : '');

                $more = ($more && (count($match[2]) > $limit));

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result, $more);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @param int    $limit  How many cast members should be returned?
     * @param bool   $bMore   Add … if there are more cast members than printed.
     * @param string $sTarget Add a target to the links?
     *
     * @return string A list with linked cast members or $notFound.
     */
    public function getCastAsUrl($limit = 0, $bMore = true, $sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_CAST);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    if (0 !== $limit && $i >= $limit) {
                        break;
                    }
                    $result[] = '<a href="http://www.imdb.com/name/' . $this->cleanString($match[1][$i]) . '/"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $this->cleanString($sName) . '</a>';
                }

                $more = ($bMore && (count($match[2]) > $limit));

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result, $more);
            }
        }

        return $this->notFound;
    }

    /**
     * @param int  $limit How many cast members should be returned?
     * @param bool $bMore  Add … if there are more cast members than printed.
     *
     * @return string  A list with cast members and their character or
     *                 $notFound.
     */
    public function getCastAndCharacter($limit = 0, $bMore = true)
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_CAST);
            $matchChar = $this->matchRegex($this->source, static::IMDB_CHAR);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    if (0 !== $limit && $i >= $limit) {
                        break;
                    }
                    $result[] = $this->cleanString($sName) . ' as ' . $this->cleanString($matchChar[1][$i]);
                }

                $more = ($bMore && (count($match[2]) > $limit));

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result, $more);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @param int    $limit  How many cast members should be returned?
     * @param bool   $bMore   Add … if there are more cast members than
     *                        printed.
     * @param string $sTarget Add a target to the links?
     *
     * @return string A list with linked cast members and their character or
     *                $notFound.
     */
    public function getCastAndCharacterAsUrl($limit = 0, $bMore = true, $sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_CAST);
            $matchChar = $this->matchRegex($this->source, static::IMDB_CHAR);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    if (0 !== $limit && $i >= $limit) {
                        break;
                    }
                    $result[] = '<a href="http://www.imdb.com/name/' . $this->cleanString($match[1][$i]) . '/"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $this->cleanString($sName) . '</a> as ' . $this->cleanString($matchChar[1][$i]);
                }

                $more = ($bMore && (count($match[2]) > $limit));

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result, $more);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @return string The certification of the movie or $notFound.
     */
    public function getCertification()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_CERTIFICATION, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string Color or $notFound.
     */
    public function getColor()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_COLOR, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string The company producing the movie or $notFound.
     */
    public function getCompany()
    {
        if (true === $this->isReady) {
            $match = $this->getCompanyAsUrl();
            if ($this->notFound !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param string $sTarget Add a target to the links?
     *
     * @return string The linked company producing the movie or $notFound.
     */
    public function getCompanyAsUrl($sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_COMPANY);
            if (isset($match[2][0])) {
                return '<a href="http://www.imdb.com/company/' . $this->cleanString($match[1][0]) . '/"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $this->cleanString($match[2][0]) . '</a>';
            }
        }

        return $this->notFound;
    }

    /**
     * @return string A list with countries or $notFound.
     */
    public function getCountry()
    {
        if (true === $this->isReady) {
            $match = $this->getCountryAsUrl();
            if ($this->notFound !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param string $sTarget Add a target to the links?
     *
     * @return string A list with linked countries or $notFound.
     */
    public function getCountryAsUrl($sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_COUNTRY);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    $result[] = '<a href="http://www.imdb.com/country/' . trim($match[1][$i]) . '/"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $this->cleanString($sName) . '</a>';
                }

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @return string A list with the creators or $notFound.
     */
    public function getCreator()
    {
        if (true === $this->isReady) {
            $match = $this->getCreatorAsUrl();
            if ($this->notFound !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param string $sTarget Add a target to the links?
     *
     * @return string A list with the linked creators or $notFound.
     */
    public function getCreatorAsUrl($sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_CREATOR, 1);
            $match = $this->matchRegex($match, static::IMDB_NAME);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    $result[] = '<a href="http://www.imdb.com/name/' . $this->cleanString($match[1][$i]) . '/"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $this->cleanString($sName) . '</a>';
                }

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @return string A list with the directors or $notFound.
     */
    public function getDirector()
    {
        if (true === $this->isReady) {
            $match = $this->getDirectorAsUrl();
            if ($this->notFound !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param string $sTarget Add a target to the links?
     *
     * @return string A list with the linked directors or $notFound.
     */
    public function getDirectorAsUrl($sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_DIRECTOR, 1);
            $match = $this->matchRegex($match, static::IMDB_NAME);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    $result[] = '<a href="http://www.imdb.com/name/' . $this->cleanString($match[1][$i]) . '/"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $this->cleanString($sName) . '</a>';
                }

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @return string A list with the genres or $notFound.
     */
    public function getGenre()
    {
        if (true === $this->isReady) {
            $match = $this->getGenreAsUrl();
            if ($this->notFound !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param string $sTarget Add a target to the links?
     *
     * @return string A list with the linked genres or $notFound.
     */
    public function getGenreAsUrl($sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_GENRE);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    $result[] = '<a href="http://www.imdb.com/Sections/Genres/' . $this->cleanString($match[1][$i]) . '/"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $this->cleanString($sName) . '</a>';
                }

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @return string A list with the languages or $notFound.
     */
    public function getLanguage()
    {
        if (true === $this->isReady) {
            $match = $this->getLanguageAsUrl();
            if ($this->notFound !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param string $sTarget Add a target to the links?
     *
     * @return string A list with the linked languages or $notFound.
     */
    public function getLanguageAsUrl($sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_LANGUAGE);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    $result[] = '<a href="http://www.imdb.com/language/' . $this->cleanString($match[1][$i]) . '"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $this->cleanString($sName) . '</a>';
                }

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @return string A list with the location or $notFound.
     */
    public function getLocation()
    {
        if (true === $this->isReady) {
            $match = $this->getLocationAsUrl();
            if ($this->notFound !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param string $sTarget Add a target to the links?
     *
     * @return string A list with the linked location or $notFound.
     */
    public function getLocationAsUrl($sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_LOCATION);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    $result[] = '<a href="http://www.imdb.com/search/title?locations=' . $this->cleanString($match[1][$i]) . '"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $this->cleanString($sName) . '</a>';
                }

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @return string The MPAA of the movie or $notFound.
     */
    public function getMpaa()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_MPAA, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string A list with the plot keywords or $notFound.
     */
    public function getPlotKeywords()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_PLOT_KEYWORDS, 1);
            if (false !== $match) {
                $result = explode('|', $this->cleanString($match));

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @param int $limit The limit.
     *
     * @return string The plot of the movie or $notFound.
     */
    public function getPlot($limit = 0)
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_PLOT, 1);
            if (false !== $match) {
                if ($limit !== 0) {
                    return $this->getShortText($this->cleanString($match), $limit);
                }

                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param string $size     Small or big poster?
     * @param bool   $download Return URL to the poster or download it?
     *
     * @return bool|string Path to the poster.
     */
    public function getPoster($size = 'small', $download = true)
    {
        if ($this->isReady === true) {
            $match = $this->matchRegex($this->source, static::IMDB_POSTER, 1);
            if ($match !== false) {
                if (strtolower($size) === 'big' && strstr($match, '@._') !== false) {
                    $match = substr($match, 0, strpos($match, '@._')) . '@.jpg';
                }
                if ($download === false) {
                    return $this->cleanString($match);
                } else {
                    $local = $this->saveImage($match, $this->id);
                    if (file_exists(dirname(__FILE__) . '/' . $local)) {
                        return $local;
                    } else {
                        return $match;
                    }
                }
            }
        }

        return $this->notFound;
    }

    /**
     * @return string The rating of the movie or $notFound.
     */
    public function getRating()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_RATING, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string The release date of the movie or $notFound.
     */
    public function getReleaseDate()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_RELEASE_DATE, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * Release date doesn't contain all the information we need to create a media and
     * we need this function that checks if users can vote target media (if can, it's released).
     *
     * @return  true If the media is released
     */
    public function isReleased()
    {
        $strReturn = $this->getReleaseDate();
        if ($strReturn == $this->notFound || $strReturn == 'Not yet released') {
            return false;
        }

        return true;
    }

    /**
     * @return string The runtime of the movie or $notFound.
     */
    public function getRuntime()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_RUNTIME, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string A list with the seasons or $notFound.
     */
    public function getSeasons()
    {
        if (true === $this->isReady) {
            $match = $this->getSeasonsAsUrl();
            if ($this->notFound !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param string $sTarget Add a target to the links?
     *
     * @return string A list with the linked seasons or $notFound.
     */
    public function getSeasonsAsUrl($sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_SEASONS);
            if (count($match[1])) {
                foreach ($match[1] as $i => $sName) {
                    $result[] = '<a href="http://www.imdb.com/title/tt' . $this->id . '/episodes?season=' . $sName . '"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $sName . '</a>';
                }

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
            }
        }

        return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound);
    }

    /**
     * @return string The sound mix of the movie or $notFound.
     */
    public function getSoundMix()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_SOUND_MIX, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string The tagline of the movie or $notFound.
     */
    public function getTagline()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_TAGLINE, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param bool $bForceLocal Try to return the original name of the movie.
     *
     * @return string The title of the movie or $notFound.
     */
    public function getTitle($bForceLocal = false)
    {
        if (true === $this->isReady) {
            if (true === $bForceLocal) {
                $match = $this->matchRegex($this->source, static::IMDB_TITLE_ORIG, 1);
                if (false !== $match && "" !== $match) {
                    return $this->cleanString($match);
                }
            }

            $match = $this->matchRegex($this->source, static::IMDB_TITLE, 1);
            $match = preg_replace('~\(\d{4}\)$~Ui', '', $match);
            if (false !== $match && "" !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param bool $bEmbed Link to player directly?
     *
     * @return string The URL to the trailer of the movie or $notFound.
     */
    public function getTrailerAsUrl($bEmbed = false)
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_TRAILER, 1);
            if (false !== $match) {
                $url = 'http://www.imdb.com/video/imdb/' . $match . '/' . ($bEmbed ? 'player' : '');

                return $this->cleanString($url);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string The IMDb URL.
     */
    public function getUrl()
    {
        if (true === $this->isReady) {
            return $this->cleanString(str_replace('combined', '', $this->url));
        }

        return $this->notFound;
    }

    /**
     * @return string The user review of the movie or $notFound.
     */
    public function getUserReview()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_USER_REVIEW, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string The votes of the movie or $notFound.
     */
    public function getVotes()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_VOTES, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string A list with the writers or $notFound.
     */
    public function getWriter()
    {
        if (true === $this->isReady) {
            $match = $this->getWriterAsUrl();
            if ($this->notFound !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @param string $sTarget Add a target to the links?
     *
     * @return string A list with the linked writers or $notFound.
     */
    public function getWriterAsUrl($sTarget = '')
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_WRITER, 1);
            $match = $this->matchRegex($match, static::IMDB_NAME);
            if (count($match[2])) {
                foreach ($match[2] as $i => $sName) {
                    $result[] = '<a href="http://www.imdb.com/name/' . $this->cleanString($match[1][$i]) . '/"' . ($sTarget ? ' target="' . $sTarget . '"' : '') . '>' . $this->cleanString($sName) . '</a>';
                }

                return $this->arrayOutput($this->arrayOutput, $this->separator, $this->notFound, $result);
            }
        }

        return $this->notFound;
    }

    /**
     * @return string The year of the movie or $notFound.
     */
    public function getYear()
    {
        if (true === $this->isReady) {
            $match = $this->matchRegex($this->source, static::IMDB_YEAR, 1);
            if (false !== $match) {
                return $this->cleanString($match);
            }
        }

        return $this->notFound;
    }

    /**
     * @return array All data.
     */
    public function getAll()
    {
        $aData = [];
        $aData['Aka'] = ['name' => 'Also Known As',
            'value' => $this->getAka()];
        $aData['Akas'] = ['name' => '(all) Also Known As',
            'value' => $this->getAkas()];
        $aData['AspectRatio'] = ['name' => 'Aspect Ratio',
            'value' => $this->getAspectRatio()];
        $aData['Awards'] = ['name' => 'Awards',
            'value' => $this->getAwards()];
        $aData['CastLinked'] = ['name' => 'Cast',
            'value' => $this->getCastAsUrl()];
        $aData['Cast'] = ['name' => 'Cast',
            'value' => $this->getCast()];
        $aData['CastAndCharacterLinked'] = ['name' => 'Cast and Character',
            'value' => $this->getCastAndCharacterAsUrl()];
        $aData['CastAndCharacter'] = ['name' => 'Cast and Character',
            'value' => $this->getCastAndCharacter()];
        $aData['Certification'] = ['name' => 'Certification',
            'value' => $this->getCertification()];
        $aData['Color'] = ['name' => 'Color',
            'value' => $this->getColor()];
        $aData['CompanyLinked'] = ['name' => 'Company',
            'value' => $this->getCompanyAsUrl()];
        $aData['Company'] = ['name' => 'Company',
            'value' => $this->getCompany()];
        $aData['CountryLinked'] = ['name' => 'Country',
            'value' => $this->getCountryAsUrl()];
        $aData['Country'] = ['name' => 'Country',
            'value' => $this->getCountry()];
        $aData['CreatorLinked'] = ['name' => 'Creator',
            'value' => $this->getCreatorAsUrl()];
        $aData['Creator'] = ['name' => 'Creator',
            'value' => $this->getCreator()];
        $aData['DirectorLinked'] = ['name' => 'Director',
            'value' => $this->getDirectorAsUrl()];
        $aData['Director'] = ['name' => 'Director',
            'value' => $this->getDirector()];
        $aData['GenreLinked'] = ['name' => 'Genre',
            'value' => $this->getGenreAsUrl()];
        $aData['Genre'] = ['name' => 'Genre',
            'value' => $this->getGenre()];
        $aData['LanguageLinked'] = ['name' => 'Language',
            'value' => $this->getLanguageAsUrl()];
        $aData['Language'] = ['name' => 'Language',
            'value' => $this->getLanguage()];
        $aData['LocationLinked'] = ['name' => 'Location',
            'value' => $this->getLocationAsUrl()];
        $aData['Location'] = ['name' => 'Location',
            'value' => $this->getLocation()];
        $aData['MPAA'] = ['name' => 'MPAA',
            'value' => $this->getMpaa()];
        $aData['PlotKeywords'] = ['name' => 'Plot Keywords',
            'value' => $this->getPlotKeywords()];
        $aData['Plot'] = ['name' => 'Plot',
            'value' => $this->getPlot()];
        $aData['Poster'] = ['name' => 'Poster',
            'value' => $this->getPoster('big')];
        $aData['Rating'] = ['name' => 'Rating',
            'value' => $this->getRating()];
        $aData['ReleaseDate'] = ['name' => 'Release Date',
            'value' => $this->getReleaseDate()];
        $aData['IsReleased'] = ['name' => 'Is released',
            'value' => $this->isReleased()];
        $aData['Runtime'] = ['name' => 'Runtime',
            'value' => $this->getRuntime()];
        $aData['SeasonsLinked'] = ['name' => 'Seasons',
            'value' => $this->getSeasonsAsUrl()];
        $aData['Seasons'] = ['name' => 'Seasons',
            'value' => $this->getSeasons()];
        $aData['SoundMix'] = ['name' => 'Sound Mix',
            'value' => $this->getSoundMix()];
        $aData['Tagline'] = ['name' => 'Tagline',
            'value' => $this->getTagline()];
        $aData['Title'] = ['name' => 'Title',
            'value' => $this->getTitle()];
        $aData['TrailerLinked'] = ['name' => 'Trailer',
            'value' => $this->getTrailerAsUrl()];
        $aData['Url'] = ['name' => 'Url',
            'value' => $this->getUrl()];
        $aData['UserReview'] = ['name' => 'User Review',
            'value' => $this->getUserReview()];
        $aData['Votes'] = ['name' => 'Votes',
            'value' => $this->getVotes()];
        $aData['WriterLinked'] = ['name' => 'Writer',
            'value' => $this->getWriterAsUrl()];
        $aData['Writer'] = ['name' => 'Writer',
            'value' => $this->getWriter()];
        $aData['Year'] = ['name' => 'Year',
            'value' => $this->getYear()];

        array_multisort($aData);

        return $aData;
    }

    /**
     * Regular expression helper.
     *
     * @param string $sContent The content to search in.
     * @param string $sPattern The regular expression.
     * @param null   $iIndex   The index to return.
     *
     * @return bool   If no match was found.
     * @return string If one match was found.
     * @return array  If more than one match was found.
     */
    public function matchRegex($sContent, $sPattern, $iIndex = null)
    {
        preg_match_all($sPattern, $sContent, $matches);
        if ($matches === false) {
            return false;
        }
        if ($iIndex !== null && is_int($iIndex)) {
            if (isset($matches[$iIndex][0])) {
                return $matches[$iIndex][0];
            }

            return false;
        }

        return $matches;
    }

    /**
     * Preferred output in responses with multiple elements
     *
     * @param bool   $arrayOutput Native array or string with separators.
     * @param string $separator   String separator.
     * @param string $notFound    Not found text.
     * @param array  $result      Original input.
     * @param bool   $more    Have more elements indicator.
     *
     * @return string Multiple results separated by selected separator string.
     * @return array  Multiple results enclosed into native array.
     */
    public function arrayOutput($arrayOutput, $separator, $notFound, $result = null, $more = false)
    {
        if ($arrayOutput) {
            if ($result == null || !is_array($result)) {
                return [];
            }

            if ($more) {
                $result[] = '…';
            }

            return $result;
        } else {
            if ($result == null || !is_array($result)) {
                return $notFound;
            }

            foreach ($result as $i => $value) {
                if (is_array($value)) {
                    $result[$i] = implode($separator, $value);
                }
            }

            return implode($separator, $result) . (($more) ? '…' : '');
        }
    }

    /**
     * @param string $input Input (eg. HTML).
     *
     * @return string Cleaned string.
     */
    public function cleanString($input)
    {
        $search = [
            'Full summary &raquo;',
            'Full synopsis &raquo;',
            'Add summary &raquo;',
            'Add synopsis &raquo;',
            'See more &raquo;',
            'See why on IMDbPro.',
        ];
        $replace = [
            '',
            '',
            '',
            '',
            '',
            '',
        ];
        $input = strip_tags($input);
        $input = str_replace('&nbsp;', ' ', $input);
        $input = str_replace($search, $replace, $input);
        $input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5);
        if (mb_substr($input, -3) === ' | ') {
            $input = mb_substr($input, 0, -3);
        }

        return ($input ? trim($input) : $this->notFound);
    }

    /**
     * @param string $text   The long text.
     * @param int    $length The maximum length of the text.
     *
     * @return string The shortened text.
     */
    public function getShortText($text, $length = 100)
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        list($short) = explode("\n", wordwrap($text, $length - 1));

        if (substr($short, -1) !== '.') {
            return $short . '…';
        }

        return $short;
    }

    /**
     * @param string $url      The URL to fetch.
     * @param bool   $download Download?
     *
     * @return bool|mixed Array on success, false on failure.
     */
    public function runCurl($url, $download = false)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_BINARYTRANSFER => ($download ? true : false),
            CURLOPT_CONNECTTIMEOUT => $this->requestTimeout,
            CURLOPT_ENCODING => '',
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_HEADER => ($download ? false : true),
            CURLOPT_HTTPHEADER => [
                'Accept-Language:' . $this->language,
                'Accept-Charset:' . 'utf-8, iso-8859-1;q=0.8',
            ],
            CURLOPT_REFERER => 'http://www.google.com',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->requestTimeout,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            CURLOPT_VERBOSE => false,
        ]);
        $output = curl_exec($curl);
        $curlInfo = curl_getinfo($curl);
        curl_close($curl);
        $curlInfo['contents'] = $output;

        if ($curlInfo['http_code'] !== 200 && $curlInfo['http_code'] !== 302) {
            if ($this->debug === true) {
                echo '<pre><b>cURL returned wrong HTTP code “' . $curlInfo['http_code'] . '”, aborting.</b></pre>';
            }

            return false;
        }

        return $curlInfo;
    }

    /**
     * @param string $url The URL to the image to download.
     * @param int    $id  The ID of the movie.
     *
     * @return string Local path.
     */
    public function saveImage($url, $id)
    {
        if (preg_match('~title_addposter.jpg|imdb-share-logo.png~', $url)) {
            return 'posters/not-found.jpg';
        }

        $filename = dirname(__FILE__) . '/posters/' . $id . '.jpg';
        if (file_exists($filename)) {
            return 'posters/' . $id . '.jpg';
        }

        $curlInfo = static::runCurl($url, true);
        $data = $curlInfo['contents'];
        if ($data === false) {
            return 'posters/not-found.jpg';
        }

        $file = fopen($filename, 'x');
        fwrite($file, $data);
        fclose($file);

        return 'posters/' . $id . '.jpg';
    }
}
