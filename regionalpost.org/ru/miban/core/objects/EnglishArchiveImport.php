<?php

class EnglishArchiveImport {

    public function run() {
        $enConf = array();
        $enConfFile = dirname(rtrim(ROOTDIR, '/\\')) . '/_conf.php';
        if (!is_file($enConfFile)) {
            throw new EPageError(EPageError::CUSTOM_ERROR, 'English _conf.php not found');
        }
        $_conf = array();
        require $enConfFile;
        $enConf = isset($_conf['mysql']) ? $_conf['mysql'] : array();
        if (empty($enConf['dbname'])) {
            throw new EPageError(EPageError::CUSTOM_ERROR, 'English database config is missing');
        }

        $en = @new mysqli($enConf['host'], $enConf['user'], $enConf['passwd'], $enConf['dbname']);
        if ($en->connect_errno) {
            throw new EPageError(EPageError::CUSTOM_ERROR, 'Cannot open English database');
        }
        $en->set_charset('utf8mb4');
        $prefix = isset($enConf['prefix']) ? $enConf['prefix'] : 'tb_';

        $ru = DatabaseProvider::provide();
        $createdParent = false;
        $imported = 0;
        $skipped = 0;

        $enParent = $this->enFetch($en, "SELECT * FROM {$prefix}pages WHERE alias='fixed-publicat' LIMIT 1");
        if (!$enParent) {
            $enParent = $this->enFetch($en, "SELECT p.* FROM {$prefix}pages p INNER JOIN {$prefix}pages c ON c.parent_id=p.id WHERE c.block_id='publicat' LIMIT 1");
        }

        $parentId = Page::archiveParentId();
        if ($parentId <= 0) {
            $parentId = $this->createRuParent($ru, $en, $prefix, $enParent);
            $createdParent = true;
        }

        $parentUrl = $this->ruScalar($ru, "SELECT url FROM #__urlcache WHERE parent_id=".intval($parentId)." AND itemtype='pages' LIMIT 1");
        if (empty($parentUrl)) {
            $parentUrl = 'publications/';
            $this->ensureUrl($ru, $parentId, 'fixed-publicat', $parentUrl, 'Collection');
        } else {
            $this->ensureCollectionVoider($ru, $parentId);
        }

        $issues = $this->enFetchAll($en, "SELECT * FROM {$prefix}pages WHERE block_id='publicat' AND id<>".intval($enParent ? $enParent['id'] : 0)." ORDER BY pos ASC, date_object DESC, id DESC");
        foreach ($issues as $issue) {
            if ($enParent && intval($issue['parent_id']) > 0 && intval($issue['parent_id']) !== intval($enParent['id'])) {
                continue;
            }
            $alias = $issue['alias'];
            if ($alias === 'fixed-publicat') {
                continue;
            }
            $exists = intval($this->ruScalar($ru, "SELECT id FROM #__pages WHERE alias=".$ru->EscapeValue($alias)." LIMIT 1"));
            if ($exists > 0) {
                $skipped++;
                continue;
            }
            $newId = $this->insertRuPage($ru, $issue, $parentId);
            if ($newId <= 0) {
                continue;
            }
            $childUrl = rtrim($parentUrl, '/') . '/' . $alias . '.html';
            $this->ensureUrl($ru, $newId, $alias, $childUrl, '');
            $file = $this->enFetch($en, "SELECT filename, thumbname FROM {$prefix}files WHERE parent_id=".intval($issue['id'])." AND type='pages' LIMIT 1");
            if ($file) {
                $this->attachSharedFile($ru, $newId, $file['filename'], $file['thumbname']);
            }
            $imported++;
        }

        $en->close();
        return array(
            'parent_id' => $parentId,
            'created_parent' => $createdParent,
            'imported' => $imported,
            'skipped' => $skipped,
            'message' => 'Russian Archive ready. Imported '.$imported.' magazine(s), skipped '.$skipped.'. PDF files were not copied.'
        );
    }

    private function createRuParent($ru, $en, $prefix, $enParent) {
        $name = 'Архив';
        $descr = '';
        $content = '';
        $pos = 1;
        $visible = 1;
        $tags = '';
        $ainfo = '';
        $binfo = '';
        if ($enParent) {
            if (!empty($enParent['name'])) {
                $name = $enParent['name'];
            }
            $descr = isset($enParent['descr']) ? $enParent['descr'] : '';
            $content = isset($enParent['content']) ? $enParent['content'] : '';
            $tags = isset($enParent['tags']) ? $enParent['tags'] : '';
            $ainfo = isset($enParent['ainfo']) ? $enParent['ainfo'] : '';
            $binfo = isset($enParent['binfo']) ? $enParent['binfo'] : '';
            $pos = isset($enParent['pos']) ? intval($enParent['pos']) : 1;
        }
        $ok = $ru->Query("INSERT INTO #__pages SET
            block_id='publicat',
            name=".$ru->EscapeValue($name).",
            descr=".$ru->EscapeValue($descr).",
            content=".$ru->EscapeValue($content).",
            pos=".intval($pos).",
            visible=".intval($visible).",
            date_modified=NOW(),
            parent_id=0,
            tags=".$ru->EscapeValue($tags).",
            alias='fixed-publicat',
            date_object=NOW(),
            ainfo=".$ru->EscapeValue($ainfo).",
            binfo=".$ru->EscapeValue($binfo).",
            cinfo='',
            tagsb=''");
        if ($ok === false) {
            throw new EPageError(EPageError::CUSTOM_ERROR, 'Could not create Russian Archive page');
        }
        $parentId = intval($ru->LastID());
        $this->ensureUrl($ru, $parentId, 'fixed-publicat', 'publications/', 'Collection');
        return $parentId;
    }

    private function insertRuPage($ru, $issue, $parentId) {
        $ok = $ru->Query("INSERT INTO #__pages SET
            block_id='publicat',
            name=".$ru->EscapeValue($issue['name']).",
            descr=".$ru->EscapeValue(isset($issue['descr']) ? $issue['descr'] : '').",
            content=".$ru->EscapeValue(isset($issue['content']) ? $issue['content'] : '').",
            pos=".intval(isset($issue['pos']) ? $issue['pos'] : 0).",
            visible=".intval(isset($issue['visible']) ? $issue['visible'] : 1).",
            date_modified=NOW(),
            parent_id=".intval($parentId).",
            tags=".$ru->EscapeValue(isset($issue['tags']) ? $issue['tags'] : '').",
            alias=".$ru->EscapeValue($issue['alias']).",
            date_object=".$ru->EscapeValue(!empty($issue['date_object']) ? $issue['date_object'] : date('Y-m-d H:i:s')).",
            ainfo=".$ru->EscapeValue(isset($issue['ainfo']) ? $issue['ainfo'] : '').",
            binfo=".$ru->EscapeValue(isset($issue['binfo']) ? $issue['binfo'] : '').",
            cinfo=".$ru->EscapeValue(isset($issue['cinfo']) ? $issue['cinfo'] : '').",
            tagsb=".$ru->EscapeValue(isset($issue['tagsb']) ? $issue['tagsb'] : '').",
            price=".floatval(isset($issue['price']) ? $issue['price'] : 0).",
            intsa=".intval(isset($issue['intsa']) ? $issue['intsa'] : 0).",
            intsb=".intval(isset($issue['intsb']) ? $issue['intsb'] : 0).",
            intsc=".intval(isset($issue['intsc']) ? $issue['intsc'] : 0));
        if ($ok === false) {
            return 0;
        }
        return intval($ru->LastID());
    }

    private function attachSharedFile($ru, $pageId, $filename, $thumbname) {
        $filename = regionalpost_public_media_url($filename);
        $thumbname = regionalpost_public_media_url($thumbname);
        $exists = intval($this->ruScalar($ru, "SELECT id FROM #__files WHERE parent_id=".intval($pageId)." AND type='pages' LIMIT 1"));
        if ($exists > 0) {
            $ru->Query("UPDATE #__files SET filename=".$ru->EscapeValue($filename).", thumbname=".$ru->EscapeValue($thumbname)." WHERE id=".$exists);
            return;
        }
        $ru->Query("INSERT INTO #__files SET parent_id=".intval($pageId).", type='pages', filename=".$ru->EscapeValue($filename).", thumbname=".$ru->EscapeValue($thumbname).", isdefault=0");
    }

    private function ensureUrl($ru, $pageId, $alias, $url, $voider) {
        $existing = intval($this->ruScalar($ru, "SELECT id FROM #__urlcache WHERE parent_id=".intval($pageId)." AND itemtype='pages' LIMIT 1"));
        $root = $this->ruFetch($ru, "SELECT id, urltop, urllevel FROM #__urlcache WHERE url='/' LIMIT 1");
        $pid = $root ? intval($root['id']) : 0;
        $urllevel = $root ? intval($root['urllevel']) + 1 : 1;
        $urltop = $root ? $root['urltop'].$pid.',' : ',';
        $urlEsc = $ru->EscapeValue($url, false);
        $aliasEsc = $ru->EscapeValue($alias, false);
        $voiderEsc = $ru->EscapeValue($voider, false);
        if ($existing > 0) {
            $ru->Query("UPDATE #__urlcache SET alias='".$aliasEsc."', url='".$urlEsc."', urlkey=CRC32('".$urlEsc."'), voider='".$voiderEsc."', componenttype='pages' WHERE id=".$existing);
            return;
        }
        $ru->Query("INSERT INTO #__urlcache SET
            parent_id=".intval($pageId).",
            componenttype='pages',
            voider='".$voiderEsc."',
            itemtype='pages',
            alias='".$aliasEsc."',
            url='".$urlEsc."',
            urlkey=CRC32('".$urlEsc."'),
            pid=".$pid.",
            date_modified=NOW(),
            cache_allow=0,
            urltop='".$ru->EscapeValue($urltop, false)."',
            urllevel=".$urllevel.",
            displaytext='',
            meta_title='',
            meta_keys='',
            meta_descr='',
            meta_robots=0");
    }

    private function ensureCollectionVoider($ru, $parentId) {
        $ru->Query("UPDATE #__urlcache SET voider='Collection', componenttype='pages' WHERE parent_id=".intval($parentId)." AND itemtype='pages'");
    }

    private function enFetch($en, $sql) {
        $res = $en->query($sql);
        if (!$res) {
            return null;
        }
        $row = $res->fetch_assoc();
        $res->free();
        return $row ? $row : null;
    }

    private function enFetchAll($en, $sql) {
        $res = $en->query($sql);
        $rows = array();
        if (!$res) {
            return $rows;
        }
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        $res->free();
        return $rows;
    }

    private function ruScalar($ru, $sql) {
        return $ru->Scalar($sql);
    }

    private function ruFetch($ru, $sql) {
        return $ru->Fetch($sql);
    }
}
