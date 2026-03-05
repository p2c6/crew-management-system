import { CrewDocument } from "./CrewDocument";
import { Document } from "./Document";
import { Rank } from "./Rank";

export type Crew = {
  id: number;
  rank: Rank;
  crewDocuments: CrewDocument;
  first_name: string;
  middle_name: string;
  last_name: string;
  address: string;
  birth_date: string;
  weight: string;
  height: string;
  email: string;
}

export type StoreCrewForm = Omit<Crew, 'rank' | 'id'> & {
  rank_id: number | string;
};

export type EditCrewForm = Omit<Crew, 'rank'> & {
  rank_id: number | string;
};

export type DeleteCrewForm = {
  id: number;
}