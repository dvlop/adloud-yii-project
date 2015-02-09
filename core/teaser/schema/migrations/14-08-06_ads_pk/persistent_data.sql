-- Constraint: ads_pk

-- ALTER TABLE ads DROP CONSTRAINT ads_pk;

ALTER TABLE ads
  ADD CONSTRAINT ads_pk PRIMARY KEY(id);

  -- Constraint: campaign_pk

-- ALTER TABLE campaign DROP CONSTRAINT campaign_pk;

ALTER TABLE campaign
  ADD CONSTRAINT campaign_pk PRIMARY KEY(id);