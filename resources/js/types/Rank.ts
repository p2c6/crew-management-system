export type Rank = {
  id: number,
  code: string,
  short_name: string,
  alias: string
}

export type StoreRankForm = {
  code: string,
  short_name: string,
  alias: string
};

export type UpdateRankForm = Rank;

export type DeleteRankForm = {
    id: number;
}