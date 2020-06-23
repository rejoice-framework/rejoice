<?php

/*
 * This file is part of the Rejoice package.
 *
 * (c) Prince Dorcis <princedorcis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prinx\Rejoice;

/**
 * Handle USSD Pagination
 *
 * Wonder if this has to be a trait. According to what is happening inside!
 * Tried an abstract class but it also resulted in some change and
 * complication of design at the user side.
 * Maybe the whole design has to be revisited.
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
trait Paginator
{
    abstract public function paginationFetch();

    abstract public function paginationTotal();

    abstract public function paginationInsertOption();

    public function before()
    {
        $previoulyRetrieved = $this->paginationGet('previously_retrieved');
        $previoulyRetrieved += count($this->paginationFetch());
        $this->paginationSave('previously_retrieved', $previoulyRetrieved);
        // $this->paginate();
    }

    public function paginate($useBack = true)
    {}

    public function actions()
    {
        return $this->paginationCurrentActions();
    }

    public function paginationCurrentActions()
    {
        $actions = [];

        if ($data = $this->paginationFetch()) {
            $fetchedCount = count($data);
            $this->paginationSave('showed_on_current_page', $fetchedCount);

            if ($fetchedCount) {
                $lastRetrievedId = intval($data[$fetchedCount - 1]['id']);
                $this->saveLastRetrievedId($lastRetrievedId);
            }

            $option = $this->paginationGet('previously_retrieved') - $this->paginationGet('showed_on_current_page');

            foreach ($data as $row) {
                $action = $this->paginationInsertOption($row, ++$option);
                $actions = array_replace($actions, $action);
            }

            if (!$this->isPaginationLastPage()) {
                $forwardAction = parent::paginateForwardOption($this->forwardTrigger());
                $actions = array_replace($actions, $forwardAction);
            }
        }

        if ($this->useBack()) {
            $back = $this->backOption($this->backTrigger());
            $actions = array_replace($actions, $back);
        }

        return $actions;
    }

    public function isPaginationFirstPage()
    {
        return ($this->paginationGet('previously_retrieved') <=
            $this->paginationTotalToShowPerPage());
    }

    public function isPaginationLastPage()
    {
        return !($this->paginationTotal() > $this->lastRetrievedId());
    }

    /*
     * On paginate forward, we set the pagination to the previous state
     */
    public function onPaginateForward()
    {
        $this->setMenuActions([]);
    }

    public function onPaginateBack()
    {
        $this->adjustPaginationSettings();
    }

    public function onMoveToNextMenu()
    {
        $ids = $this->paginationGet('last_retrieved_ids');
        array_pop($ids);
        $this->paginationSave('last_retrieved_ids', $ids);

        $previoulyRetrieved = $this->paginationGet('previously_retrieved');
        $previoulyRetrieved -= $this->paginationGet('showed_on_current_page');
        $this->paginationSave('previously_retrieved', $previoulyRetrieved);
    }

    public function onBack()
    {
        $this->onPaginateBack();
    }

    public function adjustPaginationSettings()
    {
        $this->setMenuActions([]);

        $ids = $this->paginationGet('last_retrieved_ids');
        array_pop($ids);
        array_pop($ids);
        $ids = empty($ids) ? $ids : [0];
        $this->paginationSave('last_retrieved_ids', $ids);

        $previoulyRetrieved = $this->paginationGet('previously_retrieved');
        $previoulyRetrieved -= ($this->paginationTotalToShowPerPage() +
            $this->paginationGet('showed_on_current_page'));
        $previoulyRetrieved = $previoulyRetrieved > 0 ? $previoulyRetrieved : 0;
        $this->paginationSave('previously_retrieved', $previoulyRetrieved);
    }

    public function paginationTotalShowedOnCurrentPage()
    {
        // $totalShowed = $this->paginationGet('showed_on_current_page');
        // return $totalShowed ?: count($this->paginationFetch());

        if (!isset($this->paginationTotalShowedOnCurrentPage)) {
            $this->paginationTotalShowedOnCurrentPage = count($this->paginationFetch());
        }

        return $this->paginationTotalShowedOnCurrentPage;
    }

    public function lastRetrievedId()
    {
        $ids = $this->paginationGet('last_retrieved_ids');
        return $ids ? $ids[count($ids) - 1] : 0;
    }

    public function saveLastRetrievedId($newId)
    {
        $ids = $this->paginationGet('last_retrieved_ids');
        $ids[] = $newId;
        $this->paginationSave('last_retrieved_ids', $ids);
    }

    public function paginationSave($key, $value)
    {
        $this->makePaginable();
        $pagination = $this->sessionGet('pagination');
        $pagination[$this->menuName()][$key] = $value;
        $this->sessionSave('pagination', $pagination);
    }

    public function paginationGet($key)
    {
        $this->makePaginable();
        return $this->sessionGet('pagination')[$this->menuName()][$key];
    }

    public function makePaginable()
    {
        if (
            !$this->sessionHas('pagination') ||
            !isset($this->sessionGet('pagination')[$this->menuName()])
        ) {
            $this->sessionSave('pagination', [
                $this->menuName() => [
                    'last_retrieved_ids' => [0],
                    'previously_retrieved' => 0,
                    'total' => 0,
                    'showed_on_current_page' => 0,
                ],
            ]);
        }
    }

    public function backOption($option = '0', $display = 'Back')
    {
        if ($this->isPaginationFirstPage()) {
            return parent::backOption($option, $display);
        } else {
            return parent::paginateBackOption($option, $display);
        }
    }

    public function paginationTotalToShowPerPage()
    {
        return $this->paginationTotalToShowPerPage ?? 5;
    }

    public function useBack()
    {
        return $this->useBack ?? true;
    }

    public function backTrigger()
    {
        return $this->backTrigger ?? '0';
    }

    public function forwardTrigger()
    {
        return $this->forwardTrigger ?? '00';
    }
}
