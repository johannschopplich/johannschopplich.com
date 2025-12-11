export interface ProjectFile {
  path: string;
  folder: string;
  lang: string;
  template: string;
}

export interface BlockFile {
  path: string;
  folder: string;
  lang: string;
  template: string;
}

export interface ContentBlock {
  content: Record<string, unknown>;
  id: string;
  isHidden: boolean;
  type: string;
}
